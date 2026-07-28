<tr>
    {{-- No --}}
    <td class="text-gray-400 text-xs text-center">{{ $idx + 1 }}</td>

    {{-- Perihal --}}
    <td>
        <p class="font-medium text-gray-800" style="font-size:0.8125rem; max-width:350px; line-height:1.45;">
            {{ $item->nama_informasi }}
        </p>
        @if($item->deskripsi)
            <p class="text-xs text-gray-400 mt-0.5 leading-snug" style="max-width:350px;">
                {{ Str::limit($item->deskripsi, 70) }}
            </p>
        @endif
    </td>

    {{-- Jenis --}}
    <td class="text-center">
        @if($item->jenis === 'dokumen')
            <span class="badge badge-dokumen"><i class="bi bi-file-earmark-text"></i> Dokumen</span>
        @elseif($item->jenis === 'link')
            <span class="badge badge-link"><i class="bi bi-link-45deg"></i> Link</span>
        @else
            <span class="badge badge-belum"><i class="bi bi-dash-circle"></i> Belum</span>
        @endif
    </td>

    {{-- Dokumen / Link --}}
    <td>
        @if($item->jenis === 'dokumen' && $item->file)
            <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
               class="inline-flex items-center gap-1 text-xs text-teal-600 hover:underline"
               title="{{ $item->file_name }}">
                <i class="bi bi-download"></i>
                {{ Str::limit($item->file_name, 32) }}
            </a>
        @elseif($item->jenis === 'link' && $item->url)
            <a href="{{ $item->url }}" target="_blank"
               class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline"
               style="max-width:220px; overflow:hidden; text-overflow:ellipsis; display:block; white-space:nowrap;"
               title="{{ $item->url }}">
                <i class="bi bi-box-arrow-up-right"></i>
                {{ $item->url }}
            </a>
        @else
            <span class="text-xs text-gray-300 italic">Belum ada dokumen/link</span>
        @endif
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($item->status === 'publish')
            <span class="badge badge-publish"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Publish</span>
        @else
            <span class="badge badge-draft"><i class="bi bi-circle" style="font-size:.4rem;"></i> Draft</span>
        @endif
    </td>

    {{-- Terakhir Diubah --}}
    <td>
        @if($item->hasDokumen())
            <span class="text-xs text-gray-600 block">{{ $item->updated_at->format('d M Y') }}</span>
            <span class="text-xs text-gray-400">{{ $item->updated_at->format('H:i') }}</span>
        @else
            <span class="text-xs text-gray-300">—</span>
        @endif
    </td>

    {{-- Aksi --}}
    <td class="text-center">
        @if($item->hasDokumen())
            <a href="{{ route('ppid.setiap_saat.edit', $item) }}"
               class="btn-edit" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
               id="editSetiapSaat{{ $item->id }}" title="Edit Dokumen/Link">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @else
            <a href="{{ route('ppid.setiap_saat.edit', $item) }}"
               class="btn-primary" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
               id="tambahSetiapSaat{{ $item->id }}" title="Tambah Dokumen/Link">
                <i class="bi bi-plus-lg"></i> Tambah
            </a>
        @endif
    </td>
</tr>
