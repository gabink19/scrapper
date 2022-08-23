@extends('layouts.master')
@section('content') 
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">Tokopedia Tools</div>
                            <div class="card-body">
                                <div class="card-title">
                                    <h3 class="text-center title-2">Toko & Etalase Scrapper</h3>
                                </div>
                                <hr>
                                <form action="{{url('/tokped/etalase')}}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group has-success">
                                        <label for="url_toko" class="control-label mb-1">URL Toko</label>
                                        <input id="url_toko" name="url_toko" type="text" class="form-control url_toko valid" data-val="true" data-val-required="Silahkan isi url toko yg dituju."
                                                    autocomplete="url_toko" aria-required="true" aria-invalid="false" aria-describedby="url_toko-error" required>
                                        <span class="help-block field-validation-valid" data-valmsg-for="url_toko" data-valmsg-replace="true"></span>
                                    </div>
                                </div>
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        <i class="fa fa-copy fa-lg"></i>&nbsp;
                                        <span id="payment-button-amount">Scrape Etalase Toko</span>
                                        <span id="payment-button-sending" style="display:none;">Mengambil Data…</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection