<!DOCTYPE html>
<html>
<head>
    <title>ChewytPad - {{ $note->title }}</title>
    <style>
        /* Reset & Margin Halaman */
        @page { margin: 0px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #2D2D2D; 
            margin: 0; 
            padding: 0; 
            background-color: #ffffff; 
        }

        /* Header Tetap di Atas */
        .header { 
            background-color: #EE2A7B; 
            color: #ffffff; 
            padding: 30px; 
            text-align: center; 
            border-bottom: 8px solid #CDFF30; 
        }

        .brand-name { font-size: 28px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; margin: 0; }
        .sub-header { font-size: 12px; margin-top: 5px; color: #FFD9F8; font-weight: bold; }

        /* Konten Utama */
        .container { 
            padding: 40px; 
            padding-bottom: 100px; /* Beri ruang untuk footer biar gak ketimpa */
        }

        .note-title { font-size: 32px; font-weight: bold; color: #EE2A7B; margin-bottom: 10px; line-height: 1.2; }
        
        .meta-box { 
            background-color: #FFD9F8; 
            border-left: 6px solid #CDFF30; 
            padding: 15px; 
            margin-bottom: 30px; 
            font-size: 12px; 
            color: #555; 
        }
        
        .meta-item { margin-bottom: 5px; display: block; }
        .badge-category { background-color: #EE2A7B; color: #fff; padding: 2px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        
        .content { 
            font-size: 14px; 
            line-height: 1.8; 
            color: #2D2D2D; 
            text-align: justify; 
            margin-bottom: 30px; 
        }

        /* Footer Tetap di Bawah (FIXED) */
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            height: 50px; /* Tinggi footer */
            text-align: center; 
            font-size: 10px; 
            color: #aaa; 
            background-color: white;
            border-top: 2px dashed #EE2A7B; 
            padding-top: 15px; 
            margin: 0 40px; /* Margin kiri kanan sesuai container */
            margin-bottom: 20px;
        }

        /* Gambar */
        .image-container { text-align: center; margin-bottom: 30px; margin-top: 20px; border: 2px solid #eee; padding: 10px; border-radius: 10px; background-color: #fafafa; }
        .note-image { max-width: 100%; max-height: 400px; border-radius: 5px; }
        .image-caption { margin-top: 10px; font-size: 11px; color: #666; font-style: italic; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1 class="brand-name">CHEWYTPAD.</h1>
        <div class="sub-header">Catatan Digital Gen-Z Paling Aesthetic ✨</div>
    </div>

    <!-- Konten -->
    <div class="container">
        <div class="note-title">{{ $note->title }}</div>

        <div class="meta-box">
            <span class="meta-item"><strong>Kategori:</strong> <span class="badge-category">{{ $note->category->name }}</span></span>
            <span class="meta-item"><strong>Penulis:</strong> {{ $note->user->name }}</span>
            <span class="meta-item"><strong>Tanggal:</strong> {{ $note->created_at->format('d M Y, H:i') }}</span>
        </div>

        <div class="content">
            {!! nl2br(e($note->content)) !!}
        </div>

        @if($note->image)
            <div class="image-container">
                <img src="{{ public_path('storage/' . $note->image) }}" class="note-image">
                @if($note->image_caption)
                    <div class="image-caption">"{{ $note->image_caption }}"</div>
                @endif
            </div>
        @endif
    </div>

    <!-- Footer (Ditaruh di luar container agar posisinya fixed di bawah halaman) -->
    <div class="footer">
        Dicetak otomatis dari ChewytPad System. <br> Jangan lupa simpan ide liarmu!
    </div>

</body>
</html>