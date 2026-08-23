<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Warranty Receive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .logo {
            height: 50px;
            display: block;
        }

        .header-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .watermark {
            position: absolute;
            opacity: 0.03;
            font-size: 120px;
            font-weight: bold;
            color: #000;
            z-index: 0;
            white-space: nowrap;
            pointer-events: none;
        }

        .font-semibold,
        .font-mono,
        .text-gray-900 {
            font-size: 14px;
        }

        .font-medium {
            font-size: 12px;
        }

        .warranty {
            font-size: 13px;
            font-style: italic;
        }

        /* Hide print button when printing */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .shadow-md {
                box-shadow: none !important;
            }

            #body-part {
                box-shadow: none !important;
                padding: 0 !important;
                border: 0 !important;
                margin: 0 !important;
            }

            .watermark {
                display: none !important;
            }
        }
    </style>
</head>

<body class="p-4 bg-gray-50 text-gray-800">

    <div class="bg-white p-6 max-w-4xl mx-auto shadow-lg border border-gray-200 relative" id="body-part">

        <!-- Header with Blue Background -->
        <div class="header-bg text-white p-5 rounded-t-lg mb-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center">
                    <div>
                        @php
                            $company = \App\Models\Setting::first();
                        @endphp
                        @if (!is_null($company))
                            <img src="{{ asset(config('app.logo')) }}" class="logo mb-2">
                        @endif
                        <div class="text-sm text-blue-100">{{ $company->address ?? 'Company Address' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold mb-1">WARRANTY RECEIPT</div>
                    </div>
                </div>

                <!-- Contact Info Bar -->
                <div class="grid grid-cols-3 gap-4 mt-4 text-sm pt-3 border-t border-blue-300 border-opacity-30">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span style="font-size: 13px;">01737-946600</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <span style="font-size: 13px;">{{ $company->email ?? 'info@abctraders.com' }}</span>
                    </div>
                    <div class="flex items-center justify-end">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span style="font-size: 13px;">Mon-Sat: 10:30 AM - 08:30 PM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Details -->
        <div class="mb-6">
            <div class="grid grid-cols-2 gap-6 mb-4">
                <!-- Left Column -->
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Order No:</div>
                        <div class="text-gray-900">{{ $data->order_id ?? '' }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Received By:</div>
                        <div class="text-gray-900">{{ $data->recive_by }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Received Date:</div>
                        <div class="text-gray-900">{{ Carbon\Carbon::parse($data->date_of)->format('d M, Y') ?? '' }}
                        </div>
                    </div>

                    @if (!is_null($data->cost))
                        <div class="flex items-center">
                            <div class="w-32 font-semibold text-gray-700">Warranty Cost:</div>
                            <div class="text-gray-900">{{ format_price($data->cost) }}</div>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Delivered To:</div>
                        <div class="text-gray-900">{{ $data->client_name }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Mobile No:</div>
                        <div class="text-gray-900">{{ $data->mobile }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Approved By:</div>
                        <div class="text-gray-900 font-medium">{{ config('app.name') }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-32 font-semibold text-gray-700">Complain Date:</div>
                        <div class="text-gray-900 font-medium">
                            {{ Carbon\Carbon::parse($data->date_of)->format('d-M-Y') ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="mb-6">
            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="border border-gray-300 p-2 text-left font-semibold w-16">SL</th>
                        <th class="border border-gray-300 p-2 text-left font-semibold">Product Name</th>
                        <th class="border border-gray-300 p-2 text-left font-semibold">Product Problem</th>
                        <th class="border border-gray-300 p-2 text-left font-semibold w-1/6">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data->productInfo as $index => $product)
                        <tr>
                            <td class="border border-gray-300 p-2 text-center w-16">{{ $index + 1 }}</td>

                            <td class="border border-gray-300 p-2">
                                <div style="font-weight:600;">{{ $product->product_name }}</div>
                                <div class="mt-1" style="font-size: 12px;">
                                    <div>Model: {{ $product->model }}</div>
                                    <div>S/N: {{ $product->serial_no }}</div>
                                    <div>{{ $product->change }}</div>
                                </div>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <div class="mt-1">
                                    <span class="ml-1">{{ $product->problem }}</span>
                                </div>
                            </td>
                            <td class="border border-gray-300 p-2 w-1/6">
                                {{ $product->remarks ?? '' }}
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="warranty">
            {!! App\Models\PagesContent::value('warranty_text') !!}
        </div>

        <div class="flex justify-between mt-24 gap-20">
            <div class="w-1/2 text-center">
                <div class="border-t border-gray-600 pt-2">Customer's Signature</div>
            </div>
            <div class="w-1/2 text-center">
                <div class="border-t border-gray-600 pt-2">Authorized Signature</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-xs text-gray-600">
            <div class="flex justify-center items-center space-x-6">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 512 512">
                        <path
                            d="M279.14 288l14.22-92.66h-88.91V117.33c0-25.35 12.42-50.06 52.24-50.06H295V6.26S259.5 0 225.36 0c-73.22 0-121.14 44.38-121.14 124.72V195.3H22.89V288h81.33v224h100.2V288z" />
                    </svg>
                    srlaptopshowroomraj
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2a10 10 0 100 20 10 10 0 000-20zm6.93 6h-2.07a15.9 15.9 0 00-1.1-3.12A8.02 8.02 0 0118.93 8zM12 4c.86 0 1.95 2.1 2.63 5H9.37C10.05 6.1 11.14 4 12 4zm-4.86 0.88A15.9 15.9 0 006.04 8H3.97A8.02 8.02 0 017.14 4.88zM4.07 16h2.07c.3 1.12.72 2.1 1.1 3.12A8.02 8.02 0 013.97 16zM12 20c-.86 0-1.95-2.1-2.63-5h5.26C13.95 17.9 12.86 20 12 20zm4.86-0.88A15.9 15.9 0 0017.96 16h2.07a8.02 8.02 0 01-3.17 3.12zM8.5 12a11.3 11.3 0 01.13-1.5h6.74A11.3 11.3 0 0115.5 12c0 .5-.03 1-.13 1.5H8.63C8.53 13 8.5 12.5 8.5 12z" />
                    </svg>
                    srlaptopbd.com
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="flex pt-4 justify-center mt-8 no-print border-t border-gray-300">
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg flex items-center transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
    </div>
</body>

</html>
