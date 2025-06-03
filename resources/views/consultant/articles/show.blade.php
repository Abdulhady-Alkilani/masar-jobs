@extends('layouts.app') {{-- أو layouts.consultant إذا كانت نسخة الاستشاري هي من تستدعي هذا --}}
{{-- تأكد من أن الـ Layout المستخدم (layouts.app أو layouts.consultant) يقوم بتحميل Bootstrap --}}

@section('title', $article->Title ?? 'Article Details') {{-- استخدام عنوان المقال كعنوان للصفحة --}}

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="card shadow-sm sm:rounded-lg">
            <article class="p-6 md:p-8"> {{-- زيادة padding لراحة المحتوى --}}

                {{-- عنوان المقال --}}
                <h1 class="text-3xl font-bold mb-4">{{ $article->Title }}</h1>

                {{-- معلومات المقال (المؤلف، النوع، التاريخ، وأزرار التعديل/الحذف) --}}
                <div class="text-sm text-muted mb-6 border-bottom pb-3 d-flex justify-content-between align-items-center flex-wrap"> {{-- إضافة flex-wrap --}}
                    <div>
                        <span>Published by:
                            {{-- تأكد أن الكنترولر يحمل علاقة user --}}
                            <a href="{{ route('admin.users.show', $article->user) }}" class="text-primary text-decoration-none" title="View Author Profile">
                                {{ $article->user->username ?? 'Unknown Author' }}
                            </a>
                        </span> |
                        <span>Category: <span class="badge bg-info text-dark">{{ $article->Type }}</span></span> |
                        <span>{{ $article->Date ? $article->Date->format('F j, Y') : 'N/A' }}</span>
                    </div>
                     {{-- أزرار التعديل/الحذف للمؤلف أو الأدمن (يجب أن يتم التحقق منها في الكنترولر) --}}
                     @auth
                        @if(Auth::id() === $article->UserID || (Auth::user()->type === 'Admin'))
                             <div class="btn-group btn-group-sm mt-2 mt-sm-0"> {{-- إضافة margin للتجاوب --}}
                                <a href="{{ route('consultant.articles.edit', $article) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('consultant.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                             </div>
                         @endif
                     @endauth
                </div>

                {{-- صورة المقال (إذا كانت موجودة) --}}
                @if ($article->{'Article Photo'} && Storage::disk('public')->exists($article->{'Article Photo'}))
                    <div class="mb-4 text-center">
                         <img src="{{ Storage::disk('public')->url($article->{'Article Photo'}) }}" alt="{{ $article->Title }}" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: contain;">
                    </div>
                @endif

                {{-- !!! جزء عرض ملف PDF !!! --}}
                @if ($article->pdf_attachment && Storage::disk('public')->exists($article->pdf_attachment))
                    <div class="mb-4 text-center p-3 border rounded bg-light">
                        <p class="mb-2 text-dark">Download attached document for full details:</p>
                        <a href="{{ Storage::disk('public')->url($article->pdf_attachment) }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </div>
                @endif
                {{-- !!! نهاية جزء عرض ملف PDF !!! --}}


                {{-- محتوى المقال --}}
                {{-- استخدام lead لجعل النص أكبر قليلاً و white-space: pre-wrap للحفاظ على تنسيق الأسطر الجديدة --}}
                <div class="lead mb-4" style="white-space: pre-wrap;">{{ $article->Description }}</div>


            </article>
        </div>
         <div class="mt-4 text-center">
             <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                 <i class="fas fa-arrow-left me-1"></i> Back to All Articles
             </a>
         </div>
    </div>
@endsection