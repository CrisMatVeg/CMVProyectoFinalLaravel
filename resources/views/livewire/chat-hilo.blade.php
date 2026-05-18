<div>
    @error('contenido')
        <div class="flash error"><i class="fa-solid fa-circle-xmark"></i> {{ $message }}</div>
    @enderror

    {{-- Separador respuestas --}}
    <div class="replies-header">
        <div class="replies-divider"></div>
        <span class="count-badge">
            <i class="fa-solid fa-reply"></i>
            {{ $mensajes->count() }} {{ $mensajes->count() === 1 ? 'respuesta' : 'respuestas' }}
        </span>
        <div class="replies-divider"></div>
    </div>

    {{-- Lista de mensajes --}}
    <div class="mensajes-list" id="mensajes-list">
        @forelse($mensajes as $mensaje)
            @php
                $msgColor   = $palette[$mensaje->user_id % count($palette)];
                $msgInicial = strtoupper(substr($mensaje->autor->username ?? '?', 0, 1));
                $esMio      = $mensaje->user_id === auth()->id();
            @endphp
            <div class="mensaje-bubble{{ $esMio ? ' es-mio' : '' }}" id="msg-{{ $mensaje->id }}">
                <div class="msg-avatar" style="background:{{ $msgColor }};">{{ $msgInicial }}</div>
                <div class="mensaje-content">
                    @if(!$esMio)
                        <span class="mensaje-author">{{ $mensaje->autor->username ?? '—' }}</span>
                    @endif
                    <div class="mensaje-body">
                        <div class="mensaje-texto">{{ $mensaje->contenido ?? '' }}</div>
                        <span class="mensaje-meta">
                            <span class="mensaje-time">{{ $mensaje->created_at->format('H:i') }}</span>
                            @if($mensaje->user_id === auth()->id() || $proyectoOwnerId === auth()->id())
                                <div class="msg-menu" x-data="{ open: false }">
                                    <button @click="open = !open" class="msg-menu-btn" title="Opciones">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" class="msg-menu-dropdown" x-cloak>
                                        <button wire:click="eliminarRespuesta({{ $mensaje->id }})"
                                                wire:confirm="¿Eliminar esta respuesta?"
                                                @click="open = false"
                                                class="msg-menu-item del">
                                            <i class="fa-solid fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </span>
                    </div>

                    @if($mensaje->archivos->isNotEmpty())
                        <div class="msg-attachments">
                            @foreach($mensaje->archivos as $adj)
                                @php $ico = $catIcono[$adj->categoria] ?? 'fa-file'; @endphp
                                @if($adj->categoria === 'imagen')
                                    <img src="{{ asset('storage/'.$adj->path) }}"
                                         class="img-thumb-adjunto"
                                         alt="{{ $adj->original_name }}"
                                         loading="lazy"
                                         onclick="openPreview({ dataset: { previewId: '{{ $adj->id }}', previewCat: 'imagen', previewUrl: '{{ asset('storage/'.$adj->path) }}', previewName: '{{ addslashes($adj->original_name) }}' } })">
                                @else
                                    <div class="msg-adjunto">
                                        <div class="file-icon {{ $adj->categoria }}">
                                            <i class="fa-solid {{ $ico }}"></i>
                                        </div>
                                        <div class="msg-adjunto-info">
                                            <span class="msg-adjunto-name" title="{{ $adj->original_name }}">{{ $adj->original_name }}</span>
                                            <span class="msg-adjunto-size">{{ $adj->size_formateado }}</span>
                                        </div>
                                        <div class="msg-adjunto-actions">
                                            @if(in_array($adj->categoria, ['texto','pdf','audio','video']))
                                                <button class="file-btn" title="Vista previa"
                                                        onclick="openPreview(this)"
                                                        data-preview-id="{{ $adj->id }}"
                                                        data-preview-cat="{{ $adj->categoria }}"
                                                        data-preview-url="{{ asset('storage/'.$adj->path) }}"
                                                        data-preview-name="{{ addslashes($adj->original_name) }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('foro.archivo.download', $adj->id) }}"
                                               class="file-btn" title="Descargar">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:32px;color:var(--muted);font-size:13px;">
                <i class="fa-regular fa-comment" style="font-size:1.6rem;opacity:.3;display:block;margin-bottom:10px;"></i>
                Aún no hay respuestas. Sé el primero en responder.
            </div>
        @endforelse
    </div>

    {{-- Reply box --}}
    <div class="reply-box">
        <div class="reply-compose">
            <div class="msg-avatar" style="background:{{ $meColor }};">{{ $meInicial }}</div>
            <div class="reply-right">
                <textarea wire:model="contenido"
                          rows="3"
                          placeholder="Escribe una respuesta… (Enter envía, Shift+Enter nueva línea)"
                          x-on:keydown.enter.prevent="if(!$event.shiftKey) $wire.guardarRespuesta()"></textarea>
                <div class="reply-toolbar">
                    <label for="reply-files" class="attach-label">
                        <i class="fa-solid fa-paperclip"></i> Adjuntar
                    </label>
                    <input type="file"
                           wire:model="archivos"
                           id="reply-files"
                           multiple
                           accept=".txt,.doc,.docx,.csv,.rtf,.odt,.pdf,.mp3,.wav,.ogg,.m4a,.flac,.aac,.jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.mp4,.mov,.avi,.webm,.mkv"
                           style="display:none;">
                    <span class="reply-file-names">
                        @if(!empty($archivos))
                            {{ count($archivos) }} archivo(s) seleccionado(s)
                        @endif
                    </span>
                    <button wire:click="guardarRespuesta"
                            wire:loading.attr="disabled"
                            class="btn-primary hover-lift"
                            style="width:auto;padding:8px 18px;font-size:13px;">
                        <span wire:loading.remove wire:target="guardarRespuesta">
                            <i class="fa-solid fa-paper-plane" style="margin-right:6px;"></i>Responder
                        </span>
                        <span wire:loading wire:target="guardarRespuesta">
                            <i class="fa-solid fa-spinner fa-spin" style="margin-right:6px;"></i>Enviando…
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Se ejecuta en cada actualización reactiva de Livewire (incluye nuevos mensajes por WebSocket)
        document.addEventListener('livewire:update', function () {
            var list = document.getElementById('mensajes-list');
            if (list && list.lastElementChild) {
                list.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    </script>
</div>
