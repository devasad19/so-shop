
@extends('apps.shop_master')

@section('content')

<div class="max-w-7xl mx-auto space-y-4"
     x-data="salesPOS({{ Js::from($products) }})">

    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">নতুন বিক্রি</h1>
            <p class="text-sm text-gray-500">POS Sales</p>
        </div>

        <a href="{{ route('owner.sales.index') }}"
           class="px-4 py-2 bg-gray-100 rounded-xl text-sm font-semibold">
            বিক্রির ইতিহাস
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- PRODUCT SECTION --}}
        <div class="lg:col-span-2 space-y-4">

            <div class="bg-white rounded-2xl shadow-sm border p-4">

<div class="flex gap-2">

    <input type="text"
           x-model="search"
           placeholder="পণ্যের নাম লিখুন: চাল / chal / Rice"
           autocomplete="off"
           class="w-full min-w-0 border rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none">

    <button type="button"
            @click="startVoiceSearch()"
            :disabled="listening"
            class="shrink-0 px-4 rounded-xl text-white font-semibold"
            :class="listening ? 'bg-red-500' : 'bg-emerald-600 hover:bg-emerald-700'">

        <span x-show="!listening">🎤</span>
        <span x-show="listening">শুনছি...</span>

    </button>

</div>

<p class="text-xs text-gray-500 mt-2">
    বাংলা, Banglish অথবা English লিখে খুঁজুন। ভয়েসে বাংলায় বলুন।
</p>

<p x-show="voiceMessage"
   x-text="voiceMessage"
   class="text-xs text-emerald-700 mt-2">
</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-4">

                    <template x-for="product in filteredProducts" :key="product.id">

                        <button type="button"
                                @click="addProduct(product)"
                                class="border rounded-xl p-3 text-left hover:border-emerald-500 hover:bg-emerald-50 transition">

                            <div class="font-semibold text-sm line-clamp-2"
                                 x-text="product.name_bn"></div>

                            <div class="text-emerald-700 font-bold mt-2"
                                 x-text="money(product.price)"></div>

                            <div class="text-xs text-gray-500 mt-1">
                                Stock:
                                <span x-text="product.stock"></span>
                                <span x-text="product.unit"></span>
                            </div>

                            <div class="mt-2 text-xs bg-emerald-100 text-emerald-700 rounded-lg px-2 py-1 text-center">
                                + যোগ করুন
                            </div>

                        </button>

                    </template>

                </div>

                <template x-if="filteredProducts.length === 0">
                    <div class="text-center py-8 text-gray-400">
                        কোনো পণ্য পাওয়া যায়নি।
                    </div>
                </template>

            </div>
        </div>

        {{-- CART SECTION --}}
        <div class="lg:col-span-1">

            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:sticky lg:top-4 space-y-4">

                <h2 class="font-bold text-lg">বিক্রির তালিকা</h2>

                <template x-if="cart.length === 0">
                    <div class="text-center text-gray-400 py-8">
                        কোনো পণ্য যোগ করা হয়নি।
                    </div>
                </template>

                <div class="space-y-3">

                    <template x-for="(item, index) in cart" :key="item.id">

                        <div class="border rounded-xl p-3 space-y-2">

                            <div class="flex justify-between gap-2">

                                <div class="font-semibold text-sm"
                                     x-text="item.name"></div>

                                <button type="button"
                                        @click="removeItem(index)"
                                        class="text-red-500 text-sm">
                                    ✕
                                </button>

                            </div>

                            <div class="flex justify-between text-xs text-gray-500">
                                <span>
                                    Stock:
                                    <span x-text="item.stock"></span>
                                    <span x-text="item.unit"></span>
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2">

                                <div>
                                    <label class="text-xs text-gray-500">পরিমাণ</label>

                                    <input type="number"
                                           min="0.001"
                                           step="0.001"
                                           x-model.number="item.quantity"
                                           @input="validateQuantity(item)"
                                           class="w-full border rounded-lg px-2 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-500">একক মূল্য</label>

                                    <input type="number"
                                           min="0"
                                           step="0.01"
                                           x-model.number="item.unit_price"
                                           class="w-full border rounded-lg px-2 py-2 text-sm">
                                </div>

                            </div>

                            <div class="text-right font-bold text-emerald-700"
                                 x-text="money(item.quantity * item.unit_price)"></div>

                        </div>

                    </template>

                </div>

                <form method="POST"
                      action="{{ route('owner.sales.store') }}"
                      @submit="prepareSubmit($event)">
                    @csrf

                    {{-- Hidden cart --}}
                    <template x-for="(item, index) in cart" :key="'hidden-'+item.id">

                        <div>
                            <input type="hidden"
                                   :name="'items['+index+'][shop_product_id]'"
                                   :value="item.id">

                            <input type="hidden"
                                   :name="'items['+index+'][quantity]'"
                                   :value="item.quantity">

                            <input type="hidden"
                                   :name="'items['+index+'][unit_price]'"
                                   :value="item.unit_price">
                        </div>

                    </template>

                    <div class="border-t pt-4 space-y-3">

                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <strong x-text="money(subtotal)"></strong>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Discount</label>

                            <input type="number"
                                   name="discount"
                                   min="0"
                                   step="0.01"
                                   x-model.number="discount"
                                   class="w-full border rounded-xl px-3 py-2">
                        </div>

                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span x-text="money(total)"></span>
                        </div>

                        <div>
                            <label class="text-sm font-medium">পরিশোধ</label>

                            <input type="number"
                                   name="paid_amount"
                                   min="0"
                                   step="0.01"
                                   x-model.number="paid"
                                   class="w-full border rounded-xl px-3 py-2">
                        </div>

                        <div class="flex justify-between text-red-600 font-bold">
                            <span>বাকি</span>
                            <span x-text="money(due)"></span>
                        </div>

                    </div>

                    {{-- Customer --}}
                    <div class="space-y-3 mt-4">

                        <input type="text"
                               name="customer_name"
                               placeholder="ক্রেতার নাম (ঐচ্ছিক)"
                               class="w-full border rounded-xl px-3 py-2">

                        <input type="text"
                               name="customer_phone"
                               placeholder="মোবাইল নম্বর (ঐচ্ছিক)"
                               class="w-full border rounded-xl px-3 py-2">

                        <select name="payment_method"
                                x-model="paymentMethod"
                                class="w-full border rounded-xl px-3 py-2">

                            <option value="cash">Cash</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="bank">Bank</option>
                            <option value="other">Other</option>
                            <option value="due">বাকি</option>
                        </select>

                        <textarea name="note"
                                  rows="2"
                                  placeholder="নোট (ঐচ্ছিক)"
                                  class="w-full border rounded-xl px-3 py-2"></textarea>

                    </div>

                    <button type="submit"
                            :disabled="cart.length === 0 || submitting"
                            class="w-full mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl py-3 disabled:opacity-50">

                        <span x-text="submitting ? 'প্রক্রিয়াধীন...' : 'বিক্রি সম্পন্ন করুন'"></span>

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


@endsection


@section('scripts')

 
 <script>
function salesPOS(products) {

    return {
        products: products,

        search: '',
        cart: [],

        discount: 0,
        paid: 0,

        paymentMethod: 'cash',
        submitting: false,

        listening: false,
        voiceMessage: '',
        recognition: null,

        // Text Search: বাংলা + Banglish + English
        get filteredProducts() {

            const search = String(this.search || '')
                .trim()
                .toLowerCase();

            if (!search) {
                return this.products;
            }

            return this.products.filter(product => {

                const names = [
                    product.name_bn,
                    product.name_bnlish,
                    product.name_en
                ];

                return names.some(name =>
                    String(name || '')
                        .toLowerCase()
                        .includes(search)
                );
            });
        },

        // Voice Search: শুধু বাংলা
        startVoiceSearch() {

            const SpeechRecognition =
                window.SpeechRecognition ||
                window.webkitSpeechRecognition;

            if (!SpeechRecognition) {

                this.voiceMessage =
                    'আপনার ব্রাউজারে Voice Search সমর্থিত নয়। Chrome ব্যবহার করে দেখুন।';

                return;
            }

            if (this.listening) {
                return;
            }

            this.voiceMessage = '';

            const recognition = new SpeechRecognition();

            recognition.lang = 'bn-BD';
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            this.recognition = recognition;

            recognition.onstart = () => {
                this.listening = true;
                this.voiceMessage = 'বাংলায় পণ্যের নাম বলুন...';
            };

            recognition.onresult = (event) => {

                const transcript = event.results[0][0].transcript;

                this.search = transcript.trim();

                this.voiceMessage =
                    'আপনি বলেছেন: ' + this.search;
            };

            recognition.onerror = (event) => {

                this.listening = false;

                if (event.error === 'not-allowed') {
                    this.voiceMessage =
                        'Microphone permission দিন।';
                } else if (event.error === 'no-speech') {
                    this.voiceMessage =
                        'কোনো কথা শোনা যায়নি। আবার চেষ্টা করুন।';
                } else {
                    this.voiceMessage =
                        'Voice Search-এ সমস্যা হয়েছে। আবার চেষ্টা করুন।';
                }
            };

            recognition.onend = () => {
                this.listening = false;
            };

            try {
                recognition.start();
            } catch (error) {
                this.listening = false;
                this.voiceMessage =
                    'Voice Search চালু করা যায়নি। আবার চেষ্টা করুন।';
            }
        },

        // Subtotal
        get subtotal() {

            return this.cart.reduce((sum, item) => {

                return sum +
                    (Number(item.quantity) *
                     Number(item.unit_price));

            }, 0);
        },

        // Total
        get total() {

            return Math.max(
                0,
                this.subtotal - Number(this.discount || 0)
            );
        },

        // Due
        get due() {

            return Math.max(
                0,
                this.total - Number(this.paid || 0)
            );
        },

        money(value) {

            return '৳ ' + Number(value || 0).toFixed(2);
        },

        // Add Product
        addProduct(product) {

            const existing = this.cart.find(
                item => item.id === product.id
            );

            if (existing) {

                if (existing.quantity + 1 > existing.stock) {
                    alert('পর্যাপ্ত Stock নেই!');
                    return;
                }

                existing.quantity += 1;
                return;
            }

            this.cart.push({

                id: product.id,

                name: product.name_bn,

                stock: product.stock,
                unit: product.unit,

                quantity: 1,

                unit_price: product.price
            });
        },

        // Remove Product
        removeItem(index) {

            this.cart.splice(index, 1);
        },

        // Validate Quantity
        validateQuantity(item) {

            if (item.quantity <= 0) {

                item.quantity = 0.001;
            }

            if (item.quantity > item.stock) {

                item.quantity = item.stock;

                alert('Stock-এর চেয়ে বেশি বিক্রি করা যাবে না!');
            }
        },

        // Submit
        prepareSubmit(event) {

            if (this.cart.length === 0) {

                event.preventDefault();

                alert('প্রথমে পণ্য যোগ করুন!');

                return;
            }

            if (
                this.discount < 0 ||
                this.discount > this.subtotal
            ) {

                event.preventDefault();

                alert('Discount সঠিক নয়!');

                return;
            }

            if (
                this.paid < 0 ||
                this.paid > this.total
            ) {

                event.preventDefault();

                alert('পরিশোধিত টাকার পরিমাণ সঠিক নয়!');

                return;
            }

            if (
                this.cart.some(item =>
                    item.quantity <= 0 ||
                    item.quantity > item.stock
                )
            ) {

                event.preventDefault();

                alert('পণ্যের পরিমাণ সঠিক নয়!');

                return;
            }

            this.submitting = true;
        }
    };
}
 
</script>

@endsection
