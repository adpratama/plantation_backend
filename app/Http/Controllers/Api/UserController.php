<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;
use App\Models\Periode;
use App\Models\Program;
use App\Models\Pengajuan;
use App\Models\PengajuanDanaindonesiana;
use App\Models\Pengaturan;
use App\Models\Blog;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
// mailchimp/mandrillapp
// use MailchimpTransactional\ApiClient;
// mailjet
use Mailjet\Resources;


class UserController extends Controller
{
	// list data user oleh admin
    public function index (Request $request)
    {
        $data = User::orderBy('name', 'asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function user_dashboard (Request $request)
    {
        $user = $request->user();
        // tampilkan form administrasi sesuai dengan status dibuka (dari pengaturan)gunakan nilai yg berisi id program
        $administrasi_dibuka = Pengaturan::where('nama', 'administrasi_dibuka')->select('id', 'nama', 'nilai')->first();

        $status_seleksi_fbk = Pengajuan::where('user_id', $user->id)->select('id', 'user_id', 'status')->first();
        $status_seleksi_danaindonesiana = PengajuanDanaindonesiana::with('program:id,nama,slug,administrasi')->where([['user_id', $user ->id]])->select('id', 'user_id', 'program_id', 'status')->get();
        $menu_administrasi = Pengaturan::where('nama', 'menu_administrasi')->where('status', 'aktif')->select('id', 'nama', 'nilai', 'output', 'status')->first();
        $form_pengajuan = Pengaturan::where('nama', 'form_pengajuan')->where('status', 'aktif')->select('id', 'nama', 'nilai', 'output', 'status')->first();
        $form_administrasi = Pengaturan::where('nama', 'form_administrasi')->where('status', 'aktif')->select('id', 'nama', 'nilai', 'output', 'status')->first();
        $form_laporan = Pengaturan::where('nama', 'form_laporan')->where('status', 'aktif')->select('id', 'nama', 'nilai', 'output', 'status')->first();
        $popup_fbk = Pengaturan::where('nama', 'popup_user_fbk')->where('status', 'aktif')->select('id', 'nama', 'nilai', 'output', 'status')->first();
        $popup_danaindonesiana = Pengaturan::where('nama', 'popup_user_danaindonesiana')->where('status', 'aktif')->select('id', 'nama', 'nilai', 'output', 'status')->first();
        $informasi = Blog::where('kategori', 'informasi')->limit(9)->select('id', 'judul', 'slug', 'tanggal', 'konten', 'status')->get();
        return response()->json([
            // 'status_seleksi' => $status_seleksi,
            // 'form_pengajuan' => $form_pengajuan,
            //'form_administrasi' => $form_administrasi,
            // 'form_laporan' => $form_laporan,
            // 'user' => $user,
            'status_seleksi_fbk' => $status_seleksi_fbk,
            'status_seleksi_danaindonesiana' => $status_seleksi_danaindonesiana,
            'menu_administrasi' => $menu_administrasi,
            'popup_fbk' => $popup_fbk,
            'popup_danaindonesiana' => $popup_danaindonesiana,
            'informasi' => $informasi
        ]);
    }
    // tampilkan data profil oleh admin/user sendiri
    public function show ($id)
    {
        $data = User::find($id);

        return response()->json([
            'data' => $data
        ]);
    }

    // tampilkan data profil oleh admin/user sendiri
    public function cari (Request $request)
    {
        $emailNama = $request->emailNama;
        $data = User::where('email', 'like', '%'.$emailNama.'%')->orWhere('name', 'like', '%'.$emailNama.'%')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // registrasi, buat atau update akun
    public function store (Request $request)
    {
        $this->validate($request, [
    		'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6',
            'hp' => 'required|string|min:10',
            'foto' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'name.regex' => 'Format nama hanya huruf, tidak boleh simbol, angka atau karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak benar',
            'email.unique' => 'Alamat email sudah terdaftar',
            'password.required' => 'Password harus diisi minimal 6 karakter',
            // 'password.confirmed' => 'Password dan konfirmasi password tidak cocok',
            'hp.required' => 'Nomor HP minimal 10 digit',
            'foto.required' => 'Foto harus diunggah'
        ]);

    	$role = $request->role ? $request->role : 'user';

        DB::beginTransaction();
        try {

            $user = User::updateOrCreate([
                'id' => $request->id
            ], [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'hp' => $request->hp,
                'role' => $role,
                'foto' => $request->foto
            ]);

            return response()->json([
                'info' => 'Pendaftaran akun atas nama '.$request->name.' email '.$request->email.' berhasil.'
            ], 200);

        } catch (Exception $e) {

            DB::rollback();
            return response()->json([
                'info' => 'Pendaftaran gagal'
            ], 422);

        }
    }

    public function email_verifikasi ($user_id)
    {
        $user = User::find($user_id);
        if ($user->kode_aktivasi == null) {
            $user->kode_aktivasi = Str::random(8);
            $user->save();
        }
        $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "noreply@danaindonesiana.id",
                        'Name' => "noreply"
                    ],
                    'To' => [
                        [
                            'Email' => $user->email,
                            'Name' => $user->name
                        ]
                    ],
                    'Subject' => "Kode aktivasi akun Danaindonesiana",
                    'HTMLPart' => 'kode aktivasi akun Danaindonesiana anda <span style="font-size: 18px"><strong>'.$user->kode_aktivasi.'</strong></span>, masuk ke halaman akun FBK kemudian input kode aktivasi pada form aktivasi'
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        return response()->json([
            'response' => $response->success(),
            'var_dump' => var_dump($response->getData())
        ]);
    }

    public function email_verifikasi_masal ()
    {
        $periode = Periode::where('status', 'aktif')->first();

        $users = User::whereNull('email_verified_at')->whereNotNull('kode_aktivasi')->where('periode_id', $periode->id)->get();
        foreach ($users as $user) {
            $this->email_verifikasi($user->name, $user->email, $user->kode_aktivasi);
        }
    }

    public function verifikasi (Request $request)
    {
        $this->validate($request, [
            'kode_aktivasi' => 'required',
            ],
            [
            'kode_aktivasi.required' => 'Kode aktivasi harus diisi',
            ]
        );

        $user_id = $request->user_id;
        $kode_aktivasi = $request->kode_aktivasi;

        $validasi = User::where([['id', $user_id], ['kode_aktivasi', $kode_aktivasi]])->first();

        if ($validasi != null) {
            $user = User::find($user_id);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            return response()->json([
                'info' => 'Berhasil verifikasi akun'
            ]);
        }
    }

    // login manual untuk custom response, belum ketemu cara custom response fortify
    public function login (Request $request)
    {
    	$this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak benar',
            'password.required' => 'Password minimal 6 karakter',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        $login = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($login)) {
            $user = User::where([['users.id', auth()->user()->id]])->select('id', 'name',  'email', 'role')->first();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
    }

    public function forget_password (Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json('berhasil '.$status)
                    : response()->json('gagal '.$status);
    }

    public function reset_password (Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json('berhasil '.$status)
                    : response()->json('gagal '.$status);
    }

    // cek stats user jika nuxt hot reload
    public function status ()
    {
        // $periode = Periode::where('status', 'aktif')->first();
        $id = auth()->user() ? auth()->user()->id : null;

        $user = User::where('id', $id)->firstOrFail();

        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
        } else {
            $token = null;
        }

        return response ([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.regex' => 'Format nama hanya huruf, tidak boleh simbol, angka atau karakter',
            'password.required' => 'Password harus diisi minimal 6 karakter',
        ]);

        // $periode_aktif = Periode::where('status', 'aktif')->first()->id;
        $role = $request->role ? $request->role : 'user';
        $email = $request->email;
        $kode_aktivasi = $request->kode_aktivasi ? $request->kode_aktivasi : Str::random(8);
        $cek_email = User::where('email', $email)->first() ? User::where('email', $email)->first()->email : null;

        if ($email == $cek_email) { //tidak perlu update email
            DB::beginTransaction();
            try {
                $user = User::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'name' => $request->name,
                        'password' => Hash::make($request->password),
                        'role' => $role,
                        'periode_id' => $request->periode_id,
                        'program_id' => $request->program_id,
                        'kode_aktivasi' => $kode_aktivasi
                    ]
                    );
                    if ($user) {
                        DB::commit();
                        return response()->json([
                            'info' => 'Akun telah diperbarui'
                        ], 200);
                    }
                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'info' => 'Gagal memperbarui akun'
                    ], 422);
                }
        } else {
            DB::beginTransaction();
            try {
                    $user = User::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'name' => $request->name,
                        'email' => $email,
                        'password' => Hash::make($request->password),
                        'role' => $role,
                        'periode_id' => $request->periode_id,
                        'program_id' => $request->program_id,
                        'kode_aktivasi' => $kode_aktivasi
                    ]
                    );
                    if ($user) {
                        DB::commit();
                        return response()->json([
                            'info' => 'Akun telah diperbarui'
                        ], 200);
                    }
            } catch (Exception $e) {
                DB::rollback();
                return response()->json([
                    'info' => 'Gagal memperbarui akun'
                ], 422);
            }
        }
    }

    public function export ($periode_id, $program_id)
    {
        return Excel::download(new UserExport($periode_id, $program_id), 'export_user.xlsx');
    }

    // hapus data, softdeletes
    public function destroy ($id)
    {
        $data = User::find($id);

        if($data->delete()) {
            return response()->json([
                'info' => 'Data telah dihapus.'
            ]);
        }
    }

}
