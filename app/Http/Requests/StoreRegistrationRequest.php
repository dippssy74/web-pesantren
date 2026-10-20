<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'tempat_tanggal_lahir' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string'],
            'nama_wali' => ['required', 'string', 'max:255'],
            'no_hp_wali' => ['required', 'string', 'max:20'],
            'file_kk' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'file_ijazah' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap calon santri wajib diisi.',
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 digit angka.',
            'tempat_tanggal_lahir.required' => 'Tempat dan tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'alamat.required' => 'Alamat domisili lengkap wajib diisi.',
            'nama_wali.required' => 'Nama orang tua / wali santri wajib diisi.',
            'no_hp_wali.required' => 'Nomor WhatsApp / HP wali santri wajib diisi.',
            'file_kk.mimes' => 'Berkas KK harus berformat PDF, JPG, JPEG, atau PNG.',
            'file_kk.max' => 'Ukuran berkas KK maksimal 2MB (2048 KB).',
            'file_ijazah.mimes' => 'Berkas Ijazah/Surat Keterangan harus berformat PDF, JPG, JPEG, atau PNG.',
            'file_ijazah.max' => 'Ukuran berkas Ijazah maksimal 2MB (2048 KB).',
        ];
    }
}
