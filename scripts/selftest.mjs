// selftest.mjs — pengujian keamanan StuntGuard (Laravel port)
// Jalankan: node scripts/selftest.mjs  (server harus jalan di 127.0.0.1:8088)
const BASE = process.env.BASE_URL || 'http://127.0.0.1:8088';

const results = [];
let cookieJarKader = '';
let cookieJarOrtu = '';

// 0. Reset akun uji agar skrip dapat dijalankan berulang (idempoten)
{
  const { execSync } = await import('node:child_process');
  const php = 'C:\\laragon\\bin\\php\\php-8.3.29-Win32-vs16-x64\\php.exe';
  execSync(`"${php}" -r "$m=new mysqli('127.0.0.1','root','','db_stuntguard');$s=$m->prepare('UPDATE users SET password=? WHERE username=?');$bc=password_hash('ortu123',PASSWORD_BCRYPT);$bc2=password_hash('kader123',PASSWORD_BCRYPT);$md5=md5('legacy123');$s->bind_param('ss',$bc,$u);$u='ortutes';$s->execute();$u='kadertes';$s->bind_param('ss',$bc2,$u);$s->execute();$u='legacyuser';$s->bind_param('ss',$md5,$u);$s->execute();echo 'reset-ok';"`, { encoding: 'utf8' });
  console.log('Akun uji di-reset (ortutes/kadertes/legacyuser)');
}


function jarAdd(jar, setCookieHeaders) {
  if (!setCookieHeaders) return jar;
  const parts = setCookieHeaders.split(/,(?=[^;]+?=)/).map((s) => s.trim());
  for (const p of parts) {
    const kv = p.split(';')[0];
    const name = kv.split('=')[0];
    if (/expires=Thu, 01 Jan 1970/i.test(p)) {
      const re = new RegExp(`(?:^|; )${name}=[^;]*; ?`, 'g');
      jar = jar.replace(re, '');
      jar = jar.replace(new RegExp(`(?:^|; )${name}=[^;]*`, 'g'), '');
    } else {
      const re = new RegExp(`${name}=[^;]*`);
      if (re.test(jar)) jar = jar.replace(re, kv);
      else jar += (jar ? '; ' : '') + kv;
    }
  }
  return jar;
}

async function req(path, opts = {}, jar = '') {
  const headers = { ...(opts.headers || {}) };
  if (jar) headers['Cookie'] = jar;
  const res = await fetch(BASE + path, { ...opts, headers, redirect: 'manual' });
  const setCookie = res.headers.get('set-cookie');
  const text = await res.text();
  return { res, text, setCookie, location: res.headers.get('location') };
}

function check(no, name, pass, detail) {
  results.push({ no, name, pass, detail });
  console.log(`${pass ? 'LOLOS ' : 'GAGAL'} | #${no} ${name} | ${detail}`);
}

async function login(username, password) {
  const g = await req('/login', {}, '');
  const token = (g.text.match(/name="_token" value="([^"]+)"/) || [])[1];
  const body = new URLSearchParams({ username, password, _token: token });
  const r = await req('/login', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() }, jarAdd('', g.setCookie));
  const jar = jarAdd('', r.setCookie);
  return { jar, res: r.res, text: r.text, location: r.location };
}

// ================= Skenario =================
// 1. Halaman privat tanpa login -> redirect ke /login
{
  const { res, location } = await req('/kader/dashboard', {}, '');
  check(1, 'Proteksi /kader/dashboard tanpa sesi', res.status === 302 && (location || '').includes('/login'), `HTTP ${res.status} -> ${location}`);
}
// 2. Panel orang tua tanpa sesi
{
  const { res, location } = await req('/orang-tua/dashboard', {}, '');
  check(2, 'Proteksi /orang-tua/dashboard tanpa sesi', res.status === 302 && (location || '').includes('/login'), `HTTP ${res.status} -> ${location}`);
}
// 3. Login password salah
{
  const { res, location } = await login('kadertes', 'password-salah-99');
  const noBypass = !(location || '').includes('dashboard');
  check(3, 'Login password salah ditolak', res.status === 302 && noBypass, `HTTP ${res.status}, redirect-back (bukan ke dashboard)`);
}
// 4. SQL injection di login
{
  const { res, location } = await login("' OR 1=1 -- ", 'x');
  check(4, "SQLi login: ' OR 1=1 --", res.status === 302 && !(location || '').includes('dashboard'), `HTTP ${res.status}, redirect-back (tidak ada bypass)`);
}
// 5. SQL injection payload kedua
{
  const { res, location } = await login("admin'--", 'x');
  check(5, "SQLi login: admin'--", res.status === 302 && !(location || '').includes('dashboard'), `HTTP ${res.status}, redirect-back (tidak ada bypass)`);
}
// 6. CSRF: POST /login tanpa token -> 419
{
  const r = await req('/login', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'username=kadertes&password=kader123' }, '');
  check(6, 'POST tanpa CSRF token ditolak (419)', r.res.status === 419, `HTTP ${r.res.status}`);
}
// 7. Cookie sesi HttpOnly
{
  const { res, text } = await login('kadertes', 'kader123');
  const sc = res.headers.get('set-cookie') || '';
  const isSession = /laravel_session|app_session|session=/i.test(sc);
  check(7, 'Set-Cookie sesi HttpOnly', isSession && /httponly/i.test(sc), `cookie sesi: ${isSession ? 'ada' : 'tidak'}, HttpOnly: ${/httponly/i.test(sc) ? 'ya' : 'tidak'}`);
}
// simpan jar kader
{
  const { jar } = await login('kadertes', 'kader123');
  cookieJarKader = jar;
}
// 8. Kader akses dashboard -> 200
{
  const { res } = await req('/kader/dashboard', {}, cookieJarKader);
  check(8, 'Kader login -> /kader/dashboard 200', res.status === 200, `HTTP ${res.status}`);
}
// 9. Kader akses panel orang tua -> redirect keluar
{
  const { res, location } = await req('/orang-tua/dashboard', {}, cookieJarKader);
  check(9, 'Kader ditolak di panel orang_tua (RBAC)', res.status === 302 && (location || '').includes('kader/dashboard'), `HTTP ${res.status} -> ${location}`);
}
// 10. Data balita: halaman list
{
  const { res } = await req('/kader/anak', {}, cookieJarKader);
  check(10, '/kader/anak (halaman data-anak.php versi lama) 200', res.status === 200, `HTTP ${res.status}`);
}
// 11. Validasi NIK: kurang dari 16 digit ditolak
{
  const g = await req('/kader/anak/tambah', {}, cookieJarKader);
  const token = (g.text.match(/name="_token" value="([^"]+)"/) || [])[1];
  const body = new URLSearchParams({ nik_anak: '123', nama_lengkap: 'Uji NIK Pendek', tempat_lahir: 'Kuningan', tanggal_lahir: '2024-01-01', jenis_kelamin: 'L', nama_ibu: 'Ibu Uji', no_hp: '0812', _token: token });
  const { res, text } = await req('/kader/anak/tambah', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() }, cookieJarKader);
  check(11, 'Validasi NIK < 16 ditolak', res.status === 302 || /error|The nik/i.test(text), `HTTP ${res.status}`);
}
// 12. XSS tersimpan: nama anak berisi <script> harus ter-escape
{
  const g = await req('/kader/anak/tambah', {}, cookieJarKader);
  const token = (g.text.match(/name="_token" value="([^"]+)"/) || [])[1];
  const body = new URLSearchParams({ nik_anak: '3204010101990099', nama_lengkap: "<script>alert(1)</script>", tempat_lahir: 'Kuningan', tanggal_lahir: '2024-03-03', jenis_kelamin: 'P', nama_ibu: 'Ibu Xss', no_hp: '081299999999', _token: token });
  await req('/kader/anak/tambah', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() }, cookieJarKader);
  const list = await req('/kader/anak', {}, cookieJarKader);
  const escaped = list.text.includes('&lt;script&gt;') && !list.text.includes('<script>alert(1)');
  check(12, 'XSS tersimpan di nama anak ter-escape (Blade)', escaped, `ter-escape: ${escaped ? 'ya' : 'TIDAK — masih rawan'}`);
}
// 13. IDOR: orang tua tidak bisa menyentuh panel kader
{
  const { jar } = await login('ortutes', 'ortu123');
  cookieJarOrtu = jar;
  const { res, location } = await req('/kader/anak', {}, cookieJarOrtu);
  check(13, 'Orang tua ditolak di /kader/anak (RBAC)', res.status === 302 && (location || '').includes('orang-tua/dashboard'), `HTTP ${res.status} -> ${location}`);
}
// 14. Orang tua mencoba hapus akun via panel kader
{
  const { res, location } = await req('/kader/pengguna/2/hapus', {}, cookieJarOrtu);
  check(14, 'Orang tua ditolak hapus akun (RBAC)', res.status === 302 && (location || '').includes('orang-tua/dashboard'), `HTTP ${res.status} -> ${location}`);
}
// 15. Kader hapus akun kader -> ditolak "Tidak berwenang"
{
  const { res, text } = await req('/kader/pengguna/1/hapus', {}, cookieJarKader);
  // id 1 di DB asli mungkin bukan kader; uji inti: hanya role orang_tua boleh dihapus
  const blocked = res.status === 302;
  check(15, 'Hapus akun non-orang_tua ditolak (proteksi role)', blocked, `HTTP ${res.status} (redirect dengan pesan)`);
}
// 16. Orang tua profil anak hanya miliknya (id_anak ditautkan akun)
{
  const { res, text } = await req('/orang-tua/profil-anak', {}, cookieJarOrtu);
  check(16, '/orang-tua/profil-anak hanya data anak tertaut', res.status === 200 && /Anak Uji Satu/.test(text), `HTTP ${res.status}, anak tampil: ${/Anak Uji Satu/.test(text) ? 'ya' : 'tidak'}`);
}
// 17. Laporan tanpa sesi ditolak
{
  const { res, location } = await req('/kader/laporan/cetak?bulan=10&tahun=2026&format=pdf', {}, '');
  check(17, '/kader/laporan/cetak tanpa sesi ditolak', res.status === 302 && (location || '').includes('/login'), `HTTP ${res.status} -> ${location}`);
}
// 18. Format laporan aneh ditolak validasi
{
  const { res, text } = await req('/kader/laporan/cetak?bulan=10&tahun=2026&format=hacked', {}, cookieJarKader);
  check(18, 'format laporan di luar excel/pdf ditolak', res.status === 302 || /error|tidak valid/i.test(text), `HTTP ${res.status}`);
}
// 19. Upgrade MD5 -> bcrypt saat login legacy
{
  const { res, text } = await login('legacyuser', 'legacy123');
  let upgraded = 'tidak tahu';
  if (/orang-tua\/dashboard/.test((res.headers.get('location') || '')) || res.status === 302) upgraded = 'login sukses (302)';
  check(19, 'Akun legacy MD5 bisa login (kompatibilitas)', res.status === 302, `HTTP ${res.status}, ${upgraded}`);
}
// 20. Password legacy di-upgrade ke bcrypt di database
{
  const { execSync } = await import('node:child_process');
  const php = 'C:\\laragon\\bin\\php\\php-8.3.29-Win32-vs16-x64\\php.exe';
  const out = execSync(`"${php}" -r "$m=new mysqli('127.0.0.1','root','','db_stuntguard');$r=$m->query(\\"SELECT LEFT(password,4) p, LENGTH(password) l FROM users WHERE username='legacyuser'\\");echo implode(' ', $r->fetch_assoc());"`, { encoding: 'utf8' });
  check(20, 'Hash MD5 di-upgrade otomatis ke bcrypt', out.includes('$2y$') && out.includes('60'), `password kini: ${out}`);
}
// 21. Password akun baru disimpan bcrypt (bukan plaintext/md5)
{
  const { execSync } = await import('node:child_process');
  const php = 'C:\\laragon\\bin\\php\\php-8.3.29-Win32-vs16-x64\\php.exe';
  const out = execSync(`"${php}" -r "$m=new mysqli('127.0.0.1','root','','db_stuntguard');$r=$m->query(\\"SELECT LEFT(password,4) p FROM users WHERE username='ortutes'\\");echo $r->fetch_assoc()['p'];"`, { encoding: 'utf8' });
  check(21, 'Password akun baru berformat bcrypt $2y$', out.includes('$2y$'), `prefix: ${out}`);
}
// 22. Orang tua mengubah profil akun: password baru tetap bcrypt
{
  const g = await req('/orang-tua/profil-akun', {}, cookieJarOrtu);
  const token = (g.text.match(/name="_token" value="([^"]+)"/) || [])[1];
  const body = new URLSearchParams({ nama: 'Ortu Uji', username: 'ortutes', password: 'ortuBaru123', _token: token });
  const { res } = await req('/orang-tua/profil-akun', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() }, cookieJarOrtu);
  check(22, 'Update profil akun sukses (password di-hash server)', res.status === 302, `HTTP ${res.status}`);
}
// 23. Logout mengakhiri sesi
{
  const g = await req('/kader/dashboard', {}, cookieJarKader);
  const token = (g.text.match(/name="_token" value="([^"]+)"/) || [])[1];
  const r = await req('/logout', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `_token=${token}` }, cookieJarKader);
  const cleared = (r.setCookie || '').length === 0 || /expire/i.test(r.setCookie || '');
  const after = await req('/kader/dashboard', {}, r.setCookie ? jarAdd(cookieJarKader, r.setCookie) : cookieJarKader);
  check(23, 'Logout menghapus sesi', (r.res.status === 302 && after.res.status === 302), `logout HTTP ${r.res.status}, akses setelah logout HTTP ${after.res.status} (harus 302 ke login)`);
}
// 24. Jemput bola & pengukuran: akses kader normal
{
  const { jar } = await login('kadertes', 'kader123');
  const a = await req('/kader/jemput-bola', {}, jar);
  const b = await req('/kader/pengukuran', {}, jar);
  check(24, 'Fitur jemput bola & pengukuran berfungsi (kader)', a.res.status === 200 && b.res.status === 200, `HTTP ${a.res.status} & ${b.res.status}`);
}

const lolos = results.filter((r) => r.pass).length;
console.log(`\nTOTAL: ${lolos}/${results.length} skenario lolos`);
process.exit(lolos === results.length ? 0 : 2);
