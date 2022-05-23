<?php
    // fungsi untuk membangun option list menggunakan data dari database yang dibangun menggunakan recursive
    function optionListTree($optionListClass ,$parent, $status, $indent){
        foreach ($parent as $node) {
            // setiap parent node yang memiliki child atau bukan parent node namun memiliki child akan disable click eventnya
            if($status == "hasChild" && count($node->children) || ($status == "noChild") && count($node->children)){
                echo "<li class='options ".$optionListClass."' id='optionParentId$node->parent_node' name='$node->id_node' style='margin-left: ".$indent."px; font-weight: bold; pointer-events: none;'> $node->nama_node </li>";
            }else{
                echo "<li class='options ".$optionListClass."' id='optionParentId$node->parent_node' name='$node->id_node' style='margin-left: ".$indent."px;'> $node->nama_node </li>";
            }
            
            if(count($node->children)){
                optionListTree($optionListClass, $node->children, "hasChild",$indent + 10);
            }
        }
    }

    function formatDate($date){
        return date('j F Y', strtotime($date));
    }

?>

@extends('layouts.tester')

@section('content')
    <div class="sideContainer">
        @if(Session::get('error'))
            <div class="alert alert-failed">
                {{ Session::get('error') }}
            </div>
        @endif
        <div class="testerMainHeader iconAndLabel">
            <img src="{{ asset('img/iconKonfigurasiPengujian.svg') }}">
            <h1>Konfigurasi Pengujian Tree Testing</h1>
        </div>
        <div class="testerSubHeader iconAndLabel">
            <img src="{{ asset('img/iconInformasiPengujian.svg') }}">
            <h2>Informasi Pengujian Tree Testing</h2>
        </div>
        <div class="blueBody bodyInformasiPengujian">
            <div class="informasiPengujian">
                <p>
                    <strong>Nama Website / Aplikasi :</strong> <span id="namaWebsite">{{ $informasiPengujian->nama_website }}</span>
                </p>
                <p>
                    <strong>Scope Pengujian :</strong> <span id="scopePengujian">{{ $informasiPengujian->scope_pengujian }}</span>
                </p>
                <p>
                    <strong>Profil Partisipan Pengujian :</strong> <span id="profilPartisipan">{{ $informasiPengujian->profil_partisipan }}</span>
                </p>
                <p>
                    <strong>Jumlah Minimal Partisipan :</strong> <span id="minimalPartisian">{{ $informasiPengujian->minimal_partisipan }}</span>
                </p>
                <p>
                    <strong>Tanggal Mulai Pengujian : </strong> 
                    @if($informasiPengujian->mulai_pengujian)
                        <span id="tanggalMulai">{{ formatDate($informasiPengujian->mulai_pengujian) }}</span>
                    @else
                        Tanggal Mulai Pengujian Belum Ditetapkan
                    @endif
                </p>
                <p>
                    <strong>Tanggal Akhir Pengujian : </strong>
                    @if($informasiPengujian->akhir_pengujian)
                        <span id="tanggalAkhir">{{ formatDate($informasiPengujian->akhir_pengujian) }}</span>
                    @else
                        Tanggal Akhir Pengujian Belum Ditetapkan
                    @endif
                </p>
                <p>
                    <strong>Status Pengujian :</strong>  
                    @if($informasiPengujian->status_pengujian)
                        <span class="textGreen">Sedang dalam masa pelaksanaan</span>
                    @else
                        <span class="textRed">Bukan dalam masa pelaksanaan</span>
                    @endif
                </p>
                <div class="informasiButton">
                    <a id="btnModalEditInformasi" class="btn btnOrange">
                        <img src="{{ asset('img/iconEdit.svg') }}" alt="">
                        Edit Informasi Pengujian
                    </a>
                    <a href='/tester/selesaikanPengujian' data-pesan="Pastikan anda telah melakukan export hasil karena setiap data dan konfigurasi terkait pengujian ini akan dihapus !" class="btn btnRed konfirmasiAkhiriPengujian">
                        <img src="{{ asset('img/iconAkhiriPengujian.svg') }}">
                        &nbsp;
                        Selesaikan Pengujian
                    </a>
                </div>
            </div>
            <div class="flexBetween">
                <h2>Kode Pengujian</h2>
            </div>
            <h2>{{ Session::get('kode_string').Session::get('kode_pengujian') }}</h2>
            <p>Kode diatas digunakan oleh partisipan pengujian untuk menemukan pengujian tree testing ini. Kirimkan kode tersebut kepada para calon partisipan pengujian agar mereka dapat berpartisipasi dalam pengujian ini.</p>
            <!-- <a class="btn btnDarkBlue">Mulai</a> -->
        </div>
        <div class="testerSubHeader iconAndLabel">
            <img src="{{ asset('img/iconKonfigurasiTask.svg') }}">
            <h2>Konfigurasi Task</h2>
        </div>
        <div class="blueBody bodyKonfigurasiTask">
            <div class="konfigurasiButton">
                <a class="btn btnDarkBlue" id="btnModalTambahTask"><img src="{{ asset('img/iconTambahTask.svg') }}">  Tambah Task</a>
                <a href="/tester/hapusTask/clear" class="btn btnRed konfirmasi" data-pesan="Semua task akan dihapus !"><img src="{{ asset('img/iconClear.svg') }}" alt=""> Hapus Semua Task</a>
            </div>
            <script>
                
            </script>
            <table class="table tableTask">
                <thead>
                    <th class="tableNumber">No</th>
                    <th>Task</th>
                    <th>Kriteria</th>
                    <th>Jawaban</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @if(!count($task))
                        <tr>
                            <td colspan="5" class="taskKosong">
                                <img src="{{ asset('img/iconEmptyFolder.svg') }}">
                                <h1>Data task masih kosong</h1>
                            </td>
                        </tr>
                    @else
                        @php
                            $i = 1;
                        @endphp
                        @foreach($task as $row)
                            <tr>
                                <td class="tableNumber">{{ $i }}</td>
                                <td>{{ $row->deskripsi }}</td>
                                <td>{{ $row->kriteria_task }}</td>
                                @if($row->nama_node)
                                    <td class="jawaban" name="{{ $row->id_node }}">{{ $row->nama_node }}</td>
                                @else 
                                    <td class="jawaban">Jawaban belum dipilih</td>
                                @endif
                                <td class="id_task" style="display : none;" name="id_task">{{ $row->id_task }}</td>
                                <td style="display : none;" name="direct_path">{{ $row->direct_path }}</td>
                                <td class="actionColumn">
                                    <a class="btn btnOrange btnModalEditTask"><img src="{{ asset('img/iconEdit.svg') }}"></a>
                                    <a href="/tester/hapusTask/{{ $row->id_task }}" data-pesan="Task akan dihapus secara permanen !" class="btn btnRed konfirmasi"><img src="{{ asset('img/iconDeleteBundar.svg') }}"></a>
                                </td>
                            </tr>
                            @php $i++ @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="paginationLinks">
                {{ $task->links() }}
            </div>
        </div>

        <div class="blueBody bodyGenerateTree">
            <div class="generateTreeHeader">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconGenerateTree.svg') }}">
                    <h2>Generate Tree</h2>
                </div>
                <h4>Masukan url website, selector, dan nama selector dari navbar yang ingin diambil label navigation treenya.</h4>
            </div>
            <form action="/tester/generateTree" method="GET" autocomplete="on" class="formGenerateTree">
                <div class="formElement">
                    <label>Url Website</label>
                    <input type="text" name="url" id="url" class="@error('url') redBorder @enderror" value="{{ old('url') }}" placeholder="Url Website ...">
                </div>
                <div class="formElement">
                    <label>Selector</label>
                    <div class="selectBox">
                        <div class="selectField @error('atribut') redBorder @enderror" id="selectAtribut">
                            <p>Pilih Selector</p>
                            <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                        </div>
                        <ul class="optionList hide" id="optionListAtribut">
                            <li class="options optionsAtribut">Pilih Selector</li>
                            <li class="options optionsAtribut" name="#">Id</li>
                            <li class="options optionsAtribut" name=".">Class</li>
                            <li class="options optionsAtribut" name="html">Elemen HTML</li>
                        </ul>
                    </div>
                    <input type="hidden" name="atribut" id="atribut" value="">
                </div>
                <div class="formElement">
                    <label>Nama Selector</label>
                    <input type="text" name="nama_selector" class="@error('nama_selector') redBorder @enderror" id="nama_selector" value="{{ old('nama_selector') }}" placeholder="Nama Selector ...">
                </div>
                <div>
                    <input type="submit" class="btn btnDarkBlue btnGenerateTree" value="Generate">
                </div>
            </form>
            <span>* Pastikan url yang dimasukkan tidak memerlukan login</span>
            <span>** jika tree yang digenerate tidak sesuai, anda dapat melakukan perubahan secara manual pada bagian konfigurasi tree dibawah ini</span>
        </div>

        <div class="testerSubHeader iconAndLabel">
            <img src="{{ asset('img/iconKonfigurasiPengujian.svg') }}">
            <h2>Konfigurasi Tree</h2>
        </div>
        <form action="/tester/deleteMultipleNode/" id="formDeleteNode" method="GET">
            @csrf
            <!-- <a href="/tester/hapusTree/clear" data-pesan="Semua node pada tree akan dihapus secara permanen !" class="btn btnRed konfirmasi"><img src="{{ asset('img/iconClear.svg') }}" alt=""> Hapus Tree</a> -->
            <div id="formDeleteHeader">
                <a id="submitFormDeleteNode" class="btn btnRed">
                    <img src="{{ asset('img/iconDeleteBundar.svg') }}" alt="">
                    Hapus Pilihan
                </a>
                <div id="boxPilihSemua">
                    <input type="checkbox" class="checkboxTree" id="checkAll">
                    <span>Pilih Semua</span>
                </div>
            </div>
            
            <div class="bodyKonfigurasiTree">
                <input type='text' style='margin-left: ".$indent."px;' class='addNode addRootNode' placeholder='Tambah root node baru'>
            </div>
        </form>
            

    <!-- Modal Edit Informasi -->
    <div id="modalEditInformasi" class="modal">
        <div class="modalContent">
            <div class="modalHeader" style="background: #FFBA33;">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconEdit.svg') }}">
                    <span>Edit Informasi</span>
                </div>
                <span class="closeModal">x</span>
            </div>
            <div class="modalBody">
                <form action="/tester/editInformasi" method="POST">
                    @csrf
                    <div class="formElement">
                        <label>Nama Website / Aplikasi : <span class="textRed">*</span></label>
                        <input type="text" name="nama_website" id="nama_website" class="@error('nama_website') redBorder @enderror" placeholder="Nama Website / Aplikasi ..." value="{{ @old('nama_website') }}">
                        @error('nama_website') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Scope Pengujian <span class="textRed">*</span></label>
                        <textarea name="scope_pengujian" id="scope_pengujian" class="@error('scope_pengujian') redBorder @enderror" placeholder="Scope Pengujian ..." value="{{ @old('scope_pengujian') }}" rows="3"></textarea>
                        <!-- <input type="text" name="scope_pengujian" id="scope_pengujian" class="@error('scope_pengujian') redBorder @enderror" placeholder="Scope Pengujian ..." value="{{ @old('scope_pengujian') }}"> -->
                        @error('scope_pengujian') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Profil Partisipan Pengujian<span class="textRed">*</span></label>
                        <input type="text" name="profil_partisipan" id="profil_partisipan" class="@error('profil_partisipan') redBorder @enderror" placeholder="Contoh : Mahasiswa & Pelajar" value="{{ @old('profil_partisipan') }}">
                        @error('profil_partisipan') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Jumlah Minimal Partisipan<span class="textRed">*</span></label>
                        <input type="number" name="minimal_partisipan" id="minimal_partisipan" min="0"  class="@error('minimal_partisipan') redBorder @enderror" placeholder="Contoh : 10" value="{{ @old('minimal_partisipan') }}">
                        @error('minimal_partisipan') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Tanggal Mulai Pengujian</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="@error('tanggal_mulai') redBorder @enderror" value="{{ @old('tanggal_mulai') }}">
                        @error('tanggal_mulai') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Tanggal Berakhir Pengujian</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="@error('tanggal_akhir') redBorder @enderror" value="{{ @old('tanggal_akhir') }}">
                        @error('tanggal_akhir') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="modalFooter">
                        <a class="modalBatal">Batal</a>
                        <input type="submit" value="Submit" class="btn btnOrange modalSubmit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Task -->
    <div id="modalTambahTask" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconTambahTask.svg') }}">
                    <span>Task Baru</span>
                </div>
                <span class="closeModal">x</span>
            </div>
            <div class="modalBody">
                <form action="/tester/storeTask" method="POST">
                    @csrf
                    <div class="formElement">
                        <label>Task <span class="textRed">*</span></label>
                        <textarea name="task" cols="20" rows="5" class="@error('task') redBorder @enderror" placeholder="Deskripsi Task ...">{{ @old('task') }}</textarea>
                        @error('task') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Kriteria Task <span class="textRed">*</span></label>
                        <div class="selectBox">
                            <div class="selectField @error('kriteria') redBorder @enderror" id="selectKriteria">
                                <p>Pilih Kriteria Dari Task Ini</p>
                                <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                            </div>
                            <ul class="optionList hide" id="optionListKriteria">
                                <li class="options optionsKriteria">Pilih Kriteria Dari Task Ini</li>
                                <li class="options optionsKriteria" name="Halaman yang sering digunakan">Halaman yang sering digunakan</li>
                                <li class="options optionsKriteria" name="Halaman yang memiliki kepentingan tinggi">Halaman yang memiliki kepentingan tinggi</li>
                                <li class="options optionsKriteria" name="Halaman yang dipilih secara acak">Halaman yang dipilih secara acak</li>
                            </ul>
                        </div>
                        <input type="hidden" name="kriteria" id="kriteria" value="">
                        @error('kriteria') <span class="errorMessage"> {{ $message }} </span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Jawaban</label>
                        @if(count($tree))
                            <div class="selectBox">
                                <div class="selectField" id="selectJawaban">
                                    <p>Pilih Jawaban dari Task Ini</p>
                                    <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                                </div>
                                <ul class="optionList hide" id="optionListJawaban">
                                    <li class="options optionsJawaban">Pilih Jawaban dari Task Ini</li>
                                    <?php
                                        optionListTree("optionsJawaban", $tree, "noChild", 0);
                                    ?>
                                </ul>
                            </div>
                            <input type="hidden" name="jawaban" id="jawaban" value="">
                            <input type="hidden" name="direct_path" id="directPath" value="">
                        @else
                            <input type="text" value="Tree Belum ada" disabled>
                        @endif
                    </div>
                    <div class="modalFooter">
                        <a class="modalBatal">Batal</a>
                        <input type="submit" value="Submit" class="btn btnDarkBlue modalSubmit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Edit Task -->
    <div id="modalEditTask" class="modal">
        <div class="modalContent">
            <div class="modalHeader" style="background: #FFBA33;">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconEdit.svg') }}">
                    <span>Edit Task</span>
                </div>
                <span class="closeModal">x</span>
            </div>
            <div class="modalBody">
                <form action="/tester/editTask" method="POST">
                    @csrf
                    <div class="formElement">
                        <label>Task<span class="textRed">*</span></label></label>
                        <textarea name="edit_task" cols="20" rows="5" class="@error('edit_task') redBorder @enderror" placeholder="Deskripsi Task ..."></textarea>
                        @error('edit_task') <span class="errorMessage">{{ $message }}</span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Kriteria Task<span class="textRed">*</span></label></label>
                        <div class="selectBox">
                            <div class="selectField @error('edit_kriteria') redBorder @enderror" id="selectEditKriteria">
                                <p>Pilih Kriteria Dari Task Ini</p>
                                <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                            </div>
                            <ul class="optionList hide" id="optionListEditKriteria">
                                <li class="options optionsEditKriteria">Pilih Kriteria Dari Task Ini</li>
                                <li class="options optionsEditKriteria" name="Halaman yang sering digunakan">Halaman yang sering digunakan</li>
                                <li class="options optionsEditKriteria" name="Halaman yang memiliki kepentingan tinggi">Halaman yang memiliki kepentingan tinggi</li>
                                <li class="options optionsEditKriteria" name="Halaman yang dipilih secara acak">Halaman yang dipilih secara acak</li>
                            </ul>
                        </div>
                        <input type="hidden" name="edit_kriteria" id="editKriteria" value="">
                        @error('edit_kriteria') <span class="errorMessage"> {{ $message }} </span> @enderror
                    </div>
                    <div class="formElement">
                        <label>Jawaban</label>
                        @if(count($tree))
                        <div class="selectBox">
                            <div class="selectField" id="selectEditJawaban">
                                <p>Pilih Jawaban Task Ini</p>
                                <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                            </div>
                            <ul class="optionList hide" id="optionListEditJawaban">
                                <li class="options optionsEditJawaban" value="">Pilih Jawaban Task Ini</li>
                                <?php
                                    optionListTree("optionsEditJawaban", $tree, "noChild", 0);
                                ?>
                            </ul>
                        </div>
                        <input type="hidden" name="editJawaban" id="editJawaban" value="">
                        <input type="hidden" name="direct_path" id="editDirectPath" value="">
                        @else
                            <input type="text" value="Tree Belum ada" disabled>
                        @endif
                        <input type="hidden" name="id_task" id="id_task" value="">
                    </div>

                    <div class="modalFooter">
                        <a class="modalBatal">Batal</a>
                        <input type="submit" value="Submit" class="btn btnOrange modalSubmit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Set Jawaban -->
    <div id="modalSetJawaban" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconSetJawaban.svg') }}">
                    <span>Set Jawaban</span>
                </div>
                <span class="closeModal">x</span>
            </div>
            <div class="modalBody">
                <form action="/tester/setJawaban" method="POST">
                    @csrf
                    <div class="formElement">
                        <label>Jawaban untuk task <span class="textRed">*</span></label>
                        @if(count($task))
                            <div class="selectBox">
                                <div class="selectField @error('setJawaban') redBorder @enderror" id="selectSetJawaban">
                                    <p>Pilih Task</p>
                                    <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                                </div>
                                <ul class="optionList hide" id="optionListSetJawaban">
                                    <li class="options optionsSetJawaban">Pilih Task</li>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($task as $row)
                                        <li class="options optionsSetJawaban" name="{{ $row->id_task }}">Task {{ $i }} - {{ $row->deskripsi }}</li>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" name="setJawaban" id="setJawaban" value="">
                            <input type="hidden" name="id_node" id="id_node" value="">
                            <input type="hidden" name="direct_path" id="pathSetJawaban">
                            @error('setJawaban') <span class="errorMessage"> {{ $message }} </span> @enderror
                        @else
                            <input type="text" value="Task Belum Ada" disabled>
                            <!--<input type="hidden" name="setJawaban" id="setJawaban" value="">-->
                        @endif
                    </div>

                    <div class="modalFooter">
                        <a class="modalBatal">Batal</a>
                        <input type="submit" value="Submit" class="btn btnDarkBlue modalSubmit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Node -->
    <div id="modalEditNode" class="modal">
        <div class="modalContent">
            <div class="modalHeader" style="background: #FFBA33;">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconEdit.svg') }}">
                    <span>Edit Nama Node</span>
                </div>
                <span class="closeModal">x</span>
            </div>
            <div class="modalBody">
                <form action="/tester/editNode" method="POST">
                    @csrf
                    <div class="formElement">
                        <label>Nama Node <span class="textRed">*</span></label>
                        <input type="text" name="nama_node" id="nama_node" placeholder="Edit Node">
                        <input type="hidden" name="id_node" id="idEditNode" value="">
                        @error('setJawaban') <span class="errorMessage"> {{ $message }} </span> @enderror
                    </div>

                    <div class="modalFooter">
                        <a class="modalBatal">Batal</a>
                        <input type="submit" value="Edit" class="btn btnOrange modalSubmit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', 'a.konfirmasiAkhiriPengujian', function(e){
            e.preventDefault();
            pesan = $(this).attr('data-pesan');

            // sweet alert
            Swal.fire({
                title: 'Apakah Anda Yakin ?',
                text: pesan,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya',
                cancelButtonText: 'Tidak',
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                    title: 'Apakah anda ingin menyimpan tree ?',
                    text: 'Jika tidak, tree juga akan dihapus',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya',
                    cancelButtonText: 'Tidak',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/tester/selesaikanPengujian/true";
                    } else {
                        window.location.href = "/tester/selesaikanPengujian";
                    }
                });

                }
            });
        });
        // untuk mengisi form modal edit informasi
        $("#btnModalEditInformasi").click(function(){
            namaWebsite = $("#namaWebsite").text();
            scopePengujian = $("#scopePengujian").text();
            profilPartisipan = $("#profilPartisipan").text();
            minimalPartisipan = $("#minimalPartisian").text();
            
            // mengambil tanggal dari span kemudian konversi ke date, lalu konversi kembali ke string
            tanggalMulai = new Date($("#tanggalMulai").text());
            tanggalMulai = getStringDate(tanggalMulai);
            
            tanggalAkhir = new Date($("#tanggalAkhir").text());
            tanggalAkhir = getStringDate(tanggalAkhir);

            // set value dari form edit informasi
            $("#nama_website").val(namaWebsite);
            $("#scope_pengujian").val(scopePengujian);
            $("#profil_partisipan").val(profilPartisipan);
            $("#minimal_partisipan").val(minimalPartisipan);
            $("#tanggal_mulai").val(tanggalMulai);
            $("#tanggal_akhir").val(tanggalAkhir);

            // jika tanggal mulai tidak kosong
            if($("#tanggalMulai").text() == ""){
                // set minimum tanggal mulai menjadi hari ini
                $("#tanggal_mulai").attr("min",getStringDate());
            }else{
                // jika tanggal mulai pada text lebih besar dari tanggal hari ini, maka minimum akan di set hari ini
                if(tanggalMulai > getStringDate()){
                    $("#tanggal_mulai").attr('min', getStringDate());
                }else{
                    // set minimum dari tanggal mulai dengan nilai tanggal mulai sebelumnya
                    $("#tanggal_mulai").attr('min', tanggalMulai);
                }
            }

            // melakukan checking apakah tanggal mulai null atau tidak
            if($("#tanggal_mulai").val() == ""){
                $("#tanggal_akhir").prop('disabled', true)
            }else{
                $("#tanggal_akhir").prop('disabled', false)
            }
        });

        // set tanggal_akhir tidak boleh kurang dari tanggal awal
        $("#tanggal_mulai").change(function(){
            // mengambil tanggal mulai dari value tanggal mulai
            tanggalMulai = $(this).val();
            // membuat object date berdasarkan tanggal mulai
            date = new Date(tanggalMulai);
            // menambahkan 1 hari dari tanggal mulai
            date.setDate(date.getDate() + 1);

            // merubah object date dari object menjadi string
            minTanggal = getStringDate(date);

            // menetapkan tanggal mulai+1 sebagai element input dari tanggal_akhir 
            $("#tanggal_akhir").attr("min", minTanggal);
            // meng enabled tanggal akhir
            $("#tanggal_akhir").prop('disabled', false)            
        });

        $("#tanggal_akhir").click(function(){
            // mengambil tanggal mulai
            tanggalMulai = $("#tanggal_mulai").val();
            // membuat object date berdasarkan tanggal mulai
            date = new Date(tanggalMulai);
            // menambahkan 1 hari dari tanggal mulai
            date.setDate(date.getDate() + 1);

            // merubah object date dari object menjadi string
            minTanggal = getStringDate(date);

            // menetapkan tanggal mulai+1 sebagai element input dari tanggal_akhir 
            $("#tanggal_akhir").attr("min", minTanggal);
        })

        // fungsi untuk mengambil direct path pada select yang berberntuk tree
        function getDirectPathSelect(optionClass, inputId){
            $(optionClass).click(function(){
                // stopping condition loop while jika memiliki parent
                parent = true;
                // variabel yang menyimpan element dan mengambil idnya
                thisNode = $(this).attr("id");
                // untuk menyimpan string directPath (isi array pertama adalah atribut name dari elemen ini)
                directPath = [$(this).attr("name")];
    
                // jika status parent masih true
                while(parent){
                    // regex mengambil number dari thisNode id (format id = optionParentId'id_parent')
                    getNumber = thisNode.match(/\d+/);
                    // jika regex tidak null(memiliki parent) maka akan mengambil parent idnya lalu merubah thisNode ke elemen parentnya
                    if(getNumber){
                        // mengambil parent id berdasarkan regex diatas
                        parentId = getNumber[0];
                        // memasukkan parent id ke array directPath
                        directPath.push(parentId);
                        // merubah this node element sebelumnya menjadi parent dari element tersebut
                        thisNode = $(optionClass+"[name='"+parentId+"']").attr("id");
                        // directPath = directPath+parentId;
                    }else{
                        // jika tidak memiliki parent maka parent = false, untuk menghentikan while loop
                        parent = false
                    }
                }
                // memutar balik array direct path
                directPath = directPath.reverse()
                // mengisi value pada input tpye hidden name directPath
                $(inputId).attr("value", directPath.join('->'))
            });
        }

        // mengambil direct path untuk tambah jawaban
        getDirectPathSelect(".optionsJawaban", "#directPath")
        // mengambil direct path untuk edit jawaban
        getDirectPathSelect(".optionsEditJawaban", "#editDirectPath")

        // mengambil variabel tree dari php
        parentNode = <?= $tree ?>

    </script>
    <!-- memanggil file scriptKonfigurasiTree.js (dipisah agar lebih rapih) -->
    <script src="{{ asset('js/scriptKonfigurasiTree.js') }}"></script>
@endsection