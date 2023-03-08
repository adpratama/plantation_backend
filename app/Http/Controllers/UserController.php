<?php

namespace App\Http\Controllers;

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
use App\Models\Pengajuan;
use App\Models\Pengaturan;
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

    public function user_dashboard ()
    {
        $status_seleksi = Pengajuan::where('user_id', auth()->user()->id)->select('id', 'status')->first();
        $form_pengajuan = Pengaturan::where('nama', 'form_pengajuan')->where('status', 'aktif')->first();
        $form_administrasi = Pengaturan::where('nama', 'form_administrasi')->where('status', 'aktif')->first();
        $form_laporan = Pengaturan::where('nama', 'form_laporan')->where('status', 'aktif')->first();
        $popup = Pengaturan::where('nama', 'popup_dashboard_user')->where('status', 'aktif')->get();

        return response()->json([
            'status_seleksi' => $status_seleksi,
            'form_pengajuan' => $form_pengajuan,
            'form_administrasi' => $form_administrasi,
            'form_laporan' => $form_laporan,
            'popup' => $popup
        ]);
    }

    // tampilkan data profil oleh admin/user sendiri
    public function show ($id)
    {
        if (auth()->user()->role == 'superadmin' || 'admin') {
            $data = User::find($id);
        } else {
            $data = User::where('id', auth()->user()->id)->first();
        }

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
            'password' => 'required|string|min:6|confirmed',
            'hp' => 'required|string|max:15'
            'foto' => 'required',
            // 'provinsi_id' => 'required',
            // 'kota_id' => 'required',
            // 'kecamatan_id' => 'required',
            // 'alamat' => 'required|string|max:200'
        ], [
            'name.required' => 'Nama harus diisi',
            'name.regex' => 'Format nama hanya huruf, tidak boleh simbol, angka atau karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak benar',
            'password.required' => 'Password harus diisi minimal 6 karakter',
            'hp.required' => 'Nomor hp harus diisi',
            'foto.required' => 'Foto/gambar sampul harus diunggah',
            // 'provinsi_id.required' => 'Provinsi harus diisi',
            // 'kota_id.required' => 'Kota harus diisi',
            // 'kecamatan_id.required' => 'Kecamatan harus diisi',
            // 'alamat.required' => 'Alamat harus diisi'
        ]);

        // $periode = Periode::where('status', 'aktif')->first();

        $role = $request->role ? $request->role : 'user';
        $kode_aktivasi = Str::random(8);

        DB::beginTransaction();
        try {
            $user = User::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'hp' => $request->hp,
                    // 'periode_id' => $periode->id,
                    'role' => $role,
                    // 'kode_aktivasi' => $kode_aktivasi
                    // 'provinsi_id' => $request->provinsi_id,
                    // 'kota_id' => $request->kota_id,
                    // 'kecamatan_id' => $request->kecamatan_id,
                    // 'alamat' => $request->alamat,
                ]
            );

            DB::commit();
            try {
                // event(new Registered($user));
                $this->email_verifikasi($name = $request->name, $email = $request->email, $kode_aktivasi);
                return response()->json([
                    'info' => 'Pendaftaran akun atas nama '.$request->name.' email '.$request->email.' berhasil, cek inbox/spam  email untuk aktivasi'
                ], 200);
            } catch(Exception $e) {
                return response()->json([
                    'info' => 'Pendaftaran akun atas nama '.$request->name.' email '.$request->email.' berhasil, email verifikasi gagal'
                ], 200);
            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'info' => 'Pendaftaran gagal'
            ], 422);
        }
    }

    public function email_verifikasi ($name, $email, $kode_aktivasi)
    {
        $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "noreply@fbk.id",
                        'Name' => "noreply"
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $name
                        ]
                    ],
                    'Subject' => "Kode aktivasi akun FBK",
                    'HTMLPart' => 'kode aktivasi akun FBK anda <span style="font-size: 18px"><strong>'.$kode_aktivasi.'</strong></span>, masuk ke halaman akun FBK kemudian input kode aktivasi pada form aktivasi'
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

        // $periode = Periode::where('status', 'aktif')->first();
        $cek_user_lama = User::where([['email', $request->email]])->first();
        if ($cek_user_lama == null) {
            return response()->json([
                'errors' => [
                    'login' => 'Email tidak diizinkan karena sudah terdaftar FBK sebelumnya',
                    'email' => null,
                    'password' => null
                ]
            ], 403);
        } else {
            if (Auth::attempt($login)) {
                $user = User::with('periode:id,tahun')->leftJoin('pengajuan', 'pengajuan.user_id', '=', 'users.id')->where([['users.id', auth()->user()->id], ['users.periode_id', $periode->id]])->select('users.id', 'users.periode_id', 'pengajuan.status', 'users.name', 'users.email', 'users.email_verified_at', 'users.role')->first();
                return response()->json([
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'errors' => [
                        'login' => 'Email atau password salah',
                        'email' => null,
                        'password' => null
                    ]
                ], 403);
            }
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
        $id = auth()->user() ? auth()->user()->id : null;
        $user = User::with('periode:id,tahun')->leftJoin('pengajuan', 'pengajuan.user_id', '=', 'users.id')->where('users.id', $id)->select('users.id', 'users.periode_id', 'pengajuan.status', 'users.name', 'users.email', 'users.email_verified_at', 'users.role')->first();

        return response ([
            'user' => $user
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
        $periode_aktif = Periode::where('status', 'aktif')->first()->id;
        $role = $request->role ? $request->role : 'user';
        $email = $request->email;
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
                        'periode_id' => $periode_aktif
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
                        'periode_id' => $periode_aktif
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

    public function export_excel ()
    {
        return Excel::download(new UserExport, 'export_user.xlsx');
    }

    // hapus data, softdeletes
    public function destroy ($id)
    {
        $data = User::find($id);
        if ($data->delete()) {
            return response()->json([
             'info' => 'Data telah dihapus'
            ]);
        }
    }
}

