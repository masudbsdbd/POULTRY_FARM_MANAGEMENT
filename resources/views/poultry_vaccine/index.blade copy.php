@extends('layouts.vertical', ['title' => 'Vaccine Schedule'])

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Vaccine Schedule', 'subtitle' => 'প্রতিকারের চেয়ে প্রতিরোধই উত্তম — Prevention is better than cure'])

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#broiler" role="tab">
                                <i class="fe-feather me-2"></i> Broiler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#sonali" role="tab">
                                <i class="fe-sun me-2"></i> Sonali
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#layer" role="tab">
                                <i class="fe-egg me-2"></i> Layer
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Broiler Tab -->
                        <div class="tab-pane active" id="broiler" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-primary">
                                        <tr class="text-center">
                                            <th>দিন</th>
                                            <th>রোগের নাম</th>
                                            <th>ভ্যাকসিন</th>
                                            <th>পদ্ধতি</th>
                                            <th>বিবরণ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center fw-bold">1-4</td>
                                            <td>রানীক্ষেত ও ব্রংকাইটিস</td>
                                            <td>IB + NB</td>
                                            <td class="text-center">১ চোখে ১ ফোটা</td>
                                            <td>নবজাতকের প্রথম ভ্যাকসিন</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">7-10</td>
                                            <td>গাম্বোরো</td>
                                            <td>IBD</td>
                                            <td class="text-center">১ ফোটা মুখে অথবা পানির সাথে</td>
                                            <td>প্রথম গাম্বোরো ডোজ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">14-17</td>
                                            <td>গাম্বোরো</td>
                                            <td>IBD</td>
                                            <td class="text-center">১ ফোটা মুখে অথবা পানির সাথে</td>
                                            <td>বুস্টার ডোজ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">20-21</td>
                                            <td>রানীক্ষেত</td>
                                            <td>ND</td>
                                            <td class="text-center">১ ফোটা (মুখে/চোখে) অথবা পানির সাথে</td>
                                            <td>রানীক্ষেত বুস্টার</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sonali Tab -->
                        <div class="tab-pane" id="sonali" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-warning">
                                        <tr class="text-center">
                                            <th>দিন</th>
                                            <th>রোগের নাম</th>
                                            <th>ভ্যাকসিন</th>
                                            <th>পদ্ধতি</th>
                                            <th>বিবরণ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center fw-bold">3-5</td>
                                            <td>রানীক্ষেত</td>
                                            <td>IB + NB</td>
                                            <td class="text-center">১ চোখে ১ ফোটা</td>
                                            <td>প্রাথমিক সুরক্ষা</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">10-12</td>
                                            <td>গাম্বোরো</td>
                                            <td>IBD</td>
                                            <td class="text-center">১ চোখে ১ ফোটা</td>
                                            <td>প্রথম ডোজ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">18-22</td>
                                            <td>গাম্বোরো</td>
                                            <td>IBD</td>
                                            <td class="text-center">১ ফোটা (মুখে/চোখে) অথবা পানির সাথে</td>
                                            <td>বুস্টার</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">24-26</td>
                                            <td>রানীক্ষেত</td>
                                            <td>ND</td>
                                            <td class="text-center">১ চোখে ১ ফোটা</td>
                                            <td>রানীক্ষেত বুস্টার</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">44-48</td>
                                            <td>রানীক্ষেত (প্রাদুর্ভাব হলে)</td>
                                            <td>ND</td>
                                            <td class="text-center">১ ফোটা (মুখে/চোখে) অথবা পানির সাথে</td>
                                            <td>অতিরিক্ত সুরক্ষা</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Layer Tab -->
                        <div class="tab-pane" id="layer" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-info">
                                        <tr class="text-center">
                                            <th>দিন</th>
                                            <th>রোগের নাম</th>
                                            <th>ভ্যাকসিন</th>
                                            <th>পদ্ধতি</th>
                                            <th>বিবরণ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center fw-bold">3-4</td>
                                            <td>রানীক্ষেত + ব্রংকাইটিস</td>
                                            <td>Himmvac IB+ND / VAC LSH 120</td>
                                            <td class="text-center">১ চোখে ১ ফোটা</td>
                                            <td>প্রাথমিক ডোজ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">6-8</td>
                                            <td>রানীক্ষেত + গামবোরো</td>
                                            <td>OL-VAC B+G / Dalgoban N+ Killed</td>
                                            <td class="text-center">ইঞ্জেকশন</td>
                                            <td>কম্বাইন্ড ডোজ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">9-10</td>
                                            <td>গাম্বোরো</td>
                                            <td>228 / Himmvac IBD / IBA vac</td>
                                            <td class="text-center">১ চোখে ১ ফোটা</td>
                                            <td>গাম্বোরো বুস্টার</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">12-14</td>
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
    </div>
</div>
@endsection