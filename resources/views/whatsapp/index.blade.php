<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.119-1.473-.328-.287-.583-.478-.83-.509-.244-.031-.497-.031-.745.07-.151.067-.38.249-.57.349-.128.068-.27.16-.405.245-.136.085-.286.073-.44.017-.195-.084-.768-.321-1.459-.972-.68-.646-.97-1.344-1.12-1.568-.17-.273-.039-.42.127-.57.152-.139.34-.364.505-.495.15-.12.32-.148.47-.09.198.071.792.313 1.139.593.347.28.693.73.793.888.1.159.05.29-.01.404-.05.114-.089.227-.198.352-.109.125-.23.214-.37.306-.139.092-.29.21-.435.345-.144.134-.24.283-.323.449-.084.166-.076.346.015.517.091.171.269.576.732.889.464.313.832.419 1.089.485.258.066.498.045.67.033.171-.012.497-.096.768-.356.271-.259.448-.578.535-.893.087-.315.087-.659-.043-.916-.131-.256-.396-.813-.433-.888-.037-.075-.075-.128-.15-.198-.075-.07-.159-.086-.319-.129-.159-.043-.497-.056-.997-.347zm-2.244-5.933c.263-.32.564-.78.684-1.063.125-.283.03-.657-.126-.926-.156-.27-.363-.695-.63-.927-.268-.232-.534-.321-.776-.362-.242-.04-.47-.021-.677-.126-.207-.105-.417-.288-.628-.456-.21-.168-.437-.349-.669-.349-.232 0-.379.019-.537.113-.158.094-.265.259-.352.444-.087.186-.311.602-.311.729-.001.127.125.248.271.403.146.155.325.388.43.516.104.127.176.221.244.3.066.079.115.139.179.203.066.065.142.137.21.214.068.077.125.171.171.289.047.118.083.258.125.412.043.153.087.315.155.465.066.149.17.325.311.492.143.167.322.349.495.498.174.149.357.264.515.327.157.063.313.075.457.03.145-.044.29-.125.444-.213.154-.088.332-.189.525-.278.194-.088.43-.167.694-.239.266-.071.568-.11.894-.133.328-.023.667-.01.999.035.332.045.658.132.96.26.303.128.576.31.812.534.238.224.43.477.572.741.141.264.231.537.27.795.038.259.008.49-.086.69-.095.201-.254.364-.445.487-.191.123-.42.193-.663.208-.244.015-.498-.032-.743-.102-.245-.07-.48-.173-.694-.298-.213-.125-.395-.262-.536-.401-.14-.139-.248-.27-.337-.407-.09-.136-.143-.269-.184-.407-.04-.138-.034-.293-.002-.482.03-.189.092-.413.172-.652.08-.239.193-.5.318-.754.124-.255.269-.506.42-.731.15-.226.303-.406.441-.534.137-.128.256-.212.35-.249.093-.037.144-.038.179-.026.035.012.052.038.067.06.015.022.033.073.05.143.017.07.034.16.055.26.022.101.044.218.072.345.027.127.06.272.104.44.043.167.098.358.172.576.075.218.168.468.286.751.117.283.258.587.42.9.162.312.341.621.526.909.185.287.377.553.559.786.182.232.357.433.513.595.156.161.293.29.398.387.104.097.172.145.196.163l.072.054-1.23.902-.069-.093c-.019-.025-.206-.281-.483-.7-.276-.418-.648-.974-1.076-1.62-.428-.645-.904-1.329-1.379-1.985-.476-.655-.949-1.209-1.356-1.58a6.833 6.833 0 00-.579-.472c-.179-.126-.319-.211-.406-.261l-.043-.025.019-.062.049-.166c.011-.035.022-.08.034-.131.012-.052.027-.113.043-.181.016-.069.035-.144.057-.223.022-.079.049-.166.08-.261.031-.095.068-.2.11-.314.043-.114.094-.242.152-.38.058-.138.124-.293.199-.463.074-.17.158-.358.252-.562.094-.204.202-.432.325-.681.123-.249.261-.524.412-.816.152-.292.319-.602.499-.922.181-.32.376-.655.584-.996.209-.341.427-.683.654-1.016.227-.333.454-.644.678-.923.225-.279.444-.525.649-.728.205-.203.391-.365.55-.482.159-.117.289-.193.38-.233l.139-.062.079.119c.05.075.125.195.219.348.095.153.213.342.349.557.136.215.289.454.454.708.166.254.345.523.53.799.185.276.379.559.575.84.196.281.397.564.594.84.198.276.399.547.595.805.197.258.392.5.577.718.185.218.362.408.521.562.159.154.302.276.419.361.117.085.209.14.27.164l.089.035-1.15.96z"/>
            </svg>
            {{ __('WhatsApp Connection') }}
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Status</h3>
            </div>
            <div class="p-6 text-center">
                @if($isReady)
                    <div class="text-green-600 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4 class="mt-3 text-xl font-semibold">WhatsApp Terhubung!</h4>
                        <p class="text-gray-500">Whatsapp Berhasil Terhubung</p>
                    </div>
                @elseif($qrCode)
                    <div id="qr-container">
                        <h4 class="mb-3 text-xl font-semibold">Scan QR Code</h4>
                        <p class="text-gray-500 mb-4">Buka Whatsapp dan scan qr ini.</p>
                        <img src="{{ $qrCode }}" alt="WhatsApp QR Code" class="mx-auto mb-4 max-w-[300px] rounded-lg">
                        <p class="text-sm text-gray-500 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Klik "Refresh Status" untuk melihat status.
                        </p>
                    </div>
                @else
                    <div class="text-yellow-500 mb-4">
                        <svg class="w-16 h-16 mx-auto animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <h4 class="mt-3 text-xl font-semibold">Loading...</h4>
                        <p class="text-gray-500">Mohon tunggu.</p>
                    </div>
                @endif

                <hr class="my-6">

                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="refreshStatus()" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh Status
                    </button>
                    <button type="button" onclick="restartService()" class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-lg hover:bg-yellow-200 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Restart
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function refreshStatus() {
            location.reload();
        }

        function restartService() {
            if (!confirm('Are you sure you want to restart the WhatsApp service?')) {
                return;
            }

            fetch('{{ route('admin.whatsapp.restart') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to restart service');
                });
        }

        function sendTestMessage(phone, message) {
            fetch('{{ route('admin.whatsapp.sendTest') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phone, message })
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('test-result');
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = data.success
                    ? '<div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Message sent successfully!</div>'
                    : '<div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Failed to send message</div>';
            })
            .catch(error => {
                console.error('Error:', error);
                const resultDiv = document.getElementById('test-result');
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = '<div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg">Error: ' + error.message + '</div>';
            });
        }

        document.getElementById('test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const phone = document.getElementById('test-phone').value;
            const message = document.getElementById('test-message').value;

            if (!phone || !message) {
                alert('Please fill in both phone and message');
                return;
            }

            sendTestMessage(phone, message);
        });
    </script>
    @endpush
</x-app-layout>
