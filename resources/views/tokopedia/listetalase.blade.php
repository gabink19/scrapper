@extends('layouts.master')
@section('content') 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- DATA TABLE -->
                    <h3 class="title-5 m-b-35">List Etalase Toko - {{$nama_toko['name']}}</h3>
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            @php
                                $url = base64_encode($url_product);
                            @endphp
                            <a class="au-btn au-btn-icon au-btn--green au-btn--small" href="#" role="button" data-toggle="modal" data-target="#myModal" onclick="scrapeFunc('semua','{{$url}}')" data-backdrop="static" data-keyboard="false"><i class="fa fa-copy fa-lg"></i>&nbsp;Scrape Semua Produk</a>
                        </div>
                        <div class="table-data__tool-right">
                        </div>
                    </div>
                    <div class="table-responsive table-responsive-data2">
                        <table class="table table-data2" id="datatables">
                            <thead>
                                <tr>
                                    <th style="background: #333333;color: white;">nama etalase</th>
                                    <th style="background: #333333;color: white;">jumlah produk</th>
                                    <th style="background: #333333;color: white;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $key => $val)
                                @if(!empty($val))
                                @php
                                    $url = base64_encode($val['category_url']);
                                @endphp
                                <tr class="tr-shadow">
                                    <td id="name_{{$key}}">{{$val['category_name']}}</td>
                                    <td id="total_{{$key}}">{{$val['total_product']}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <a class="au-btn au-btn-icon au-btn--green au-btn--small" href="#" role="button" data-toggle="modal" data-target="#myModal" onclick="scrapeFunc('{{$key}}','{{$url}}')"  data-backdrop="static" data-keyboard="false"><i class="fa fa-copy fa-lg"></i>&nbsp;Scrape</a>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- END DATA TABLE -->
                </div>
            </div>
        </div>
    </div>         
</div>   

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titleModalEtalase" style="text-align: center;width: 100%;"></h4>
      </div>
      <div class="modal-body">
        <div class="row" id="loader" style="display:none;">
            <div class="page-loader__spin"></div>
            <h4 class="text-center title-2" style="position: absolute;top: 55%;left: 38%;z-index: 100000;">Mohon Tunggu...</h4>
        </div>
        <div class="row" id="scrapeTokped">
            <div class="col-lg-12">
                <div class="card">
                        <div class="card-body">
                            <form action="#" method="post" id='formScrapTokped'>
                                {{ csrf_field() }}
                                <div class="form-group has-success">
                                    <label for="nama_file" class="control-label mb-1">Nama File</label>
                                    <input id="nama_file" name="nama_file" type="text" class="form-control nama_file valid" data-val="true" title="Silahkan isi Nama File untuk menyimpan hasil scrape." required>
                                </div>
                                <div class="form-group has-success">
                                    <label for="etalase" class="control-label mb-1">Etalase</label>
                                    <input id="etalase" name="etalase" type="text" class="form-control etalase valid" data-val="true" required disabled>
                                </div>
                                <div class="form-group has-success">
                                    <label for="urutan" class="control-label mb-1">Urutkan Produk</label>
                                    <select name="urutan" id="urutan" class="form-control">
                                        @php
                                        $array_sort = [
                                            '23' => 'Paling Sesuai',
                                            '2' => 'Terbaru',
                                            '10' => 'Termahal',
                                            '9' => 'Termurah',
                                            '8' => 'Penjualan Terbanyak',
                                            '11' => 'Ulasan',
                                        ];
                                        @endphp
                                        @foreach($array_sort as $key => $val)
                                        <option value="{{$key}}">{{$val}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group has-success">
                                    <label for="stok" class="control-label mb-1">Stok</label>
                                    <input id="stok" name="stok" type="number" class="form-control stok valid" data-val="true" title="Silahkan isi Stok Produk yg anda inginkan." required>
                                </div>
                                <div class="form-group has-success">
                                    <label for="mark_up" class="control-label mb-1">Mark Up Harga (%)</label>
                                    <input id="mark_up" name="mark_up" type="number" class="form-control mark_up valid" data-val="true" title="Silahkan isi Mark Up Harga yg anda inginkan." min="0" minlength="1" required>
                                </div>
                                <div class="form-group has-success">
                                    <label for="template" class="control-label mb-1">Template Tujuan</label>
                                    <select name="template" id="template" class="form-control">
                                        @php
                                        $array_sort = [
                                            'akulaku' => 'Akulaku',
                                        ];
                                        @endphp
                                        @foreach($array_sort as $key => $val)
                                        <option value="{{$key}}">{{$val}}</option>
                                        @endforeach
                                        <option value="{{$key}}" disabled >Lazada (Coming Soon)</option>
                                        <option value="{{$key}}" disabled>Shopee (Coming Soon)</option>
                                        <option value="{{$key}}" disabled>Tokopedia (Coming Soon)</option>
                                    </select>
                                </div>
                                <input id="url" name="url" type="hidden">
                                <div>
                                    <button id="download-button" type="submit" class="btn btn-lg btn-info btn-block">
                                        <i class="fa fa-download fa-lg"></i>&nbsp;
                                        <span id="download-button-amount">Download Hasil Scrape</span>
                                    </button>
                                    <button id="close-button" type="button" class="btn btn-lg btn-secondary btn-block"data-dismiss="modal">
                                        <span id="close-button-amount">Tutup</span>
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
</div> 
<script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js" defer></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js" defer></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js" defer></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js" defer></script>
<script type="text/javascript">
    $(document).ready( function () {
        $('#datatables').DataTable();

        $("form").submit(function (event) {
          var formData = $('#formScrapTokped').serialize();
          $('#loader').show();
          $("#scrapeTokped").children().bind('click', function(){ return false; });
          $(document.body).css({'cursor' : 'wait'});
          $.ajax({
            type: "POST",
            url: "{{url('/tokped/exportScrap')}}",
            data: formData,
            xhrFields: {
                responseType: 'blob'
            },
            success: function (result) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(result);
                a.href = url;
                a.download = $('#nama_file').val()+'.xlsx';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                $('#loader').hide();
                $("#scrapeTokped").children().unbind('click');
                $(document.body).css({'cursor' : 'default'});
            }
          }).done(function (data) {
              $('#loader').hide();
              $("#scrapeTokped").children().unbind('click');
              $(document.body).css({'cursor' : 'default'});
          });
          event.preventDefault();
        });
    } );

    function scrapeFunc(id,url_base64) {
        if (id != 'semua') {
            $('#titleModalEtalase').text('Scrape Etalase : '+$('#name_'+id).text());
            $('#etalase').val($('#name_'+id).text());
        }else{
            $('#titleModalEtalase').text('Scrape Semua Etalase');
            $('#etalase').val('Scrape Semua Produk');
        }
        $('#url').val(url_base64);
    }
</script>  
@endsection   
