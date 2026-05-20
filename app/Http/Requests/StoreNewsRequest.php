<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'thumbnail' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,webp'],
            'status' => ['required', Rule::in(['draft', 'published', 'hidden'])],
            'is_featured' => ['nullable', 'boolean'],
            'is_breaking_news' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'lalin_category' => ['nullable', 'string', 'max:255'],
            'lalin_status' => ['nullable', 'string', 'max:50'],
            'lalin_estimated_end' => ['nullable', 'date'],
            'lalin_alternative_route' => ['nullable', 'string'],
            'lalin_location' => ['nullable', 'string', 'max:500'],
            'lalin_source' => ['nullable', 'string', 'max:255'],
            'lalin_contact' => ['nullable', 'string', 'max:255'],
            'tanggal_kejadian' => ['nullable', 'date'],
            'waktu_kejadian' => ['nullable', 'string'],
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
            'title.required' => 'Judul berita wajib diisi.',
            'title.max' => 'Judul berita maksimal 500 karakter.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'content.required' => 'Konten berita wajib diisi.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB.',
            'thumbnail.mimes' => 'Format thumbnail harus JPEG, PNG, atau WebP.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }
}
