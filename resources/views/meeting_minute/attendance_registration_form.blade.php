{{-- resources/views/meeting_minute/attendance_registration_form.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حضور الاجتماع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .signature-container { border: 2px dashed #ddd; }
        canvas { width: 100%; height: 200px; background: white; }
        .signed-badge { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">تسجيل حضور الاجتماع</h3>
                        <h5 class="mb-0">{{ $meetingMinute->title }}</h5>
                        <p class="mb-0">{{ $meetingMinute->date?->format('Y-m-d') }}</p>
                    </div>
                    
                    <div class="card-body">
                        {{-- QR Code Section --}}
                        <div class="text-center mb-4">
                            <h5>QR Code للحضور</h5>
                            <div class="d-inline-block p-3 bg-white">
                                {!! $qrCode !!}
                            </div>
                            <p class="text-muted mt-2">يمكن مسح هذا الكود لتسجيل الحضور</p>
                        </div>

                        {{-- Already Signed Attendees --}}
                        @if(count($signedAttendees) > 0)
                        <div class="mb-4">
                            <h5>تم تسجيل الحضور من قبل:</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($signedAttendees as $attendee)
                                    <span class="badge signed-badge p-2">{{ $attendee }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <hr>

                        {{-- Registration Form --}}
                        <form id="signatureForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">يرجى كتابة اسمك كما هو مسجل في قائمة الحضور</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">التوقيع</label>
                                <div class="signature-container p-3 mb-2">
                                    <canvas id="signatureCanvas"></canvas>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary" id="clearSignature">
                                        مسح التوقيع
                                    </button>
                                    <button type="button" class="btn btn-warning" id="undoSignature">
                                        تراجع
                                    </button>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                بعد التوقيع سيتم حفظ حضورك بشكل تلقائي
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                    <i class="fas fa-signature"></i> تأكيد التوقيع
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Signature Pad
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'white',
            penColor: 'black'
        });

        // Adjust canvas on resize
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        // Clear signature
        document.getElementById('clearSignature').addEventListener('click', () => {
            signaturePad.clear();
        });

        // Undo last stroke
        document.getElementById('undoSignature').addEventListener('click', () => {
            const data = signaturePad.toData();
            if (data) {
                data.pop();
                signaturePad.fromData(data);
            }
        });

        // Submit form
        document.getElementById('signatureForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (signaturePad.isEmpty()) {
                alert('يرجى إضافة توقيعك');
                return;
            }
            
            const name = document.getElementById('name').value;
            if (!name.trim()) {
                alert('يرجى إدخال اسمك');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري الحفظ...';

            try {
                // Convert signature to base64
                const signatureData = signaturePad.toDataURL('image/png');
                
                const response = await fetch('{{ route("meeting_minute.store_signature", $meetingMinute->public_token) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        name: name,
                        signature: signatureData
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('تم تسجيل توقيعك بنجاح!');
                    location.reload();
                } else {
                    throw new Error(result.message || 'حدث خطأ');
                }
            } catch (error) {
                alert('حدث خطأ: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-signature"></i> تأكيد التوقيع';
            }
        });
    </script>
</body>
</html>