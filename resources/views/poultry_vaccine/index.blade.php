@extends('layouts.vertical', ['title' => 'Vaccine Schedule'])

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', [
        'title' => 'Vaccine Schedule',
        'subtitle' => 'প্রতিকারের চেয়ে প্রতিরোধই উত্তম — Prevention is better than cure'
    ])

    <div class="row g-4">

        <!-- Broiler Section -->
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4 text-center">
                    <i class="fe-feather fs-1 mb-2 d-block"></i>
                    <h3 class="mb-0 fw-bold">Broiler Vaccine Schedule</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-center fw-bold">
                                    <th width="10%">দিন</th>
                                    <th width="25%">রোগের নাম</th>
                                    <th width="20%">ভ্যাকসিন</th>
                                    <th width="25%">পদ্ধতি</th>
                                    <th width="20%">বিবরণ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-primary">1-4</td>
                                    <td>রানীক্ষেত ও ব্রংকাইটিস</td>
                                    <td>IB + NB</td>
                                    <td class="text-center">১ চোখে ১ ফোটা</td>
                                    <td>নবজাতকের প্রথম সুরক্ষা</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">7-10</td>
                                    <td>গাম্বোরো</td>
                                    <td>IBD</td>
                                    <td class="text-center">১ ফোটা মুখে অথবা পানির সাথে</td>
                                    <td>প্রথম গাম্বোরো ডোজ</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">14-17</td>
                                    <td>গাম্বোরো</td>
                                    <td>IBD</td>
                                    <td class="text-center">১ ফোটা মুখে অথবা পানির সাথে</td>
                                    <td>বুস্টার ডোজ</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">20-21</td>
                                    <td>রানীক্ষেত</td>
                                    <td>ND</td>
                                    <td class="text-center">১ ফোটা (মুখে/চোখে) অথবা পানির সাথে</td>
                                    <td>রানীক্ষেত বুস্টার</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sonali Section -->
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-warning text-white py-4 text-center">
                    <i class="fe-sun fs-1 mb-2 d-block"></i>
                    <h3 class="mb-0 fw-bold">Sonali Vaccine Schedule</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-center fw-bold">
                                    <th width="10%">দিন</th>
                                    <th width="25%">রোগের নাম</th>
                                    <th width="20%">ভ্যাকসিন</th>
                                    <th width="25%">পদ্ধতি</th>
                                    <th width="20%">বিবরণ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-warning">3-5</td>
                                    <td>রানীক্ষেত</td>
                                    <td>IB + NB</td>
                                    <td class="text-center">১ চোখে ১ ফোটা</td>
                                    <td>প্রাথমিক সুরক্ষা</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-warning">10-12</td>
                                    <td>গাম্বোরো</td>
                                    <td>IBD</td>
                                    <td class="text-center">১ চোখে ১ ফোটা</td>
                                    <td>প্রথম ডোজ</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-warning">18-22</td>
                                    <td>গাম্বোরো</td>
                                    <td>IBD</td>
                                    <td class="text-center">১ ফোটা (মুখে/চোখে) অথবা পানির সাথে</td>
                                    <td>বুস্টার ডোজ</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-warning">24-26</td>
                                    <td>রানীক্ষেত</td>
                                    <td>ND</td>
                                    <td class="text-center">১ চোখে ১ ফোটা</td>
                                    <td>রানীক্ষেত বুস্টার</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-warning">44-48</td>
                                    <td>রানীক্ষেত (প্রাদুর্ভাব হলে)</td>
                                    <td>ND</td>
                                    <td class="text-center">১ ফোটা (মুখে/চোখে) অথবা পানির সাথে</td>
                                    <td>অতিরিক্ত সুরক্ষা</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layer Section -->
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-info text-white py-4 text-center">
                    <i class="fe-egg fs-1 mb-2 d-block"></i>
                    <h3 class="mb-0 fw-bold">Layer Vaccine Schedule</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-center fw-bold">
                                    <th width="10%">দিন</th>
                                    <th width="25%">রোগের নাম</th>
                                    <th width="20%">ভ্যাকসিন</th>
                                    <th width="25%">পদ্ধতি</th>
                                    <th width="20%">বিবরণ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-info">3-4</td>
                                    <td>রানীক্ষেত + ব্রংকাইটিস</td>
                                    <td>Himmvac IB+ND / VAC LSH 120</td>
                                    <td class="text-center">১ চোখে ১ ফোটা</td>
                                    <td>প্রাথমিক ডোজ</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-info">6-8</td>
                                    <td>রানীক্ষেত + গামবোরো</td>
                                    <td>OL-VAC B+G / Dalgoban N+ Killed</td>
                                    <td class="text-center">ইঞ্জেকশন</td>
                                    <td>কম্বাইন্ড ডোজ</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-info">9-10</td>
                                    <td>গাম্বোরো</td>
                                    <td>228 / Himmvac IBD / IBA vac</td>
                                    <td class="text-center">১ চোখে ১ ফোটা</td>
                                    <td>গাম্বোরো বুস্টার</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-info">12-14</td>
                                    <td>মারেক্স</td>
                                    <td>BIO Marex HVT</td>
                                    <td class="text-center">ইঞ্জেকশন</td>
                                    <td>মারেক্স প্রতিরোধ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection