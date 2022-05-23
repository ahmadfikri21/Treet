// fugsi untuk membangun custom select bx
// parameter : inputId = id dari input type hidden, selectFieldId = id dari selectField, optionList, optionListId = id dari option list(box yang mengandung options), options = pilihan yang ada pada select
function customSelectbox(inputId, selectFieldId, optionListId, options){
    // nama selectbox
    var selectBox = $(selectFieldId);
    // isi text dari selectbox
    var selectText = $(selectFieldId+" p");
    // wrapper pilihan yang ada di selectbox
    var optionList = $(optionListId)
    // pilihan yang ada
    var options = $(options);
    // panah dropdown
    var dropdownArrow = $(selectFieldId+" .dropdownArrow");
    // name untuk input type hidden
    var name = $(inputId);

    // event selectbox di klik
    selectBox.on('click', function(){
        // menyembunyikan optionlist
        // if(!optionList.hasClass('hide')){
        //     optionList.addClass('hide');
        // }else{
        //     optionList.removeClass('hide');
        // }
        optionList.toggleClass('hide');
        optionList.css("width",selectBox.outerWidth()+"px")
        // menambahkan focus border
        optionList.toggleClass('blueFocusBorder');
        selectBox.toggleClass('blueFocusBorder');
        // memutar dropdown arrow
        dropdownArrow.toggleClass('rotate');
        // fungsi untuk highlight option yang sedang dipilih
        options.each(function(){
            if($(this).text().includes(selectText.text())){
                $(this).addClass('selected');
            }else{
                $(this).removeClass('selected');
            }
        })
    })

    // foreach loop dari seluruh data pilihan pada selectbox
    options.each(function(){
        // event untuk setiap pilihan yang ada
        options.click(function(){
            // mengisi selectbox sesuai dengan pilihan yg diklik
            selectText.html(this.textContent);
            // mengisi value dari input type hidden
            name.val($(this).attr('name'));
            // // mengatur agar input type hidden dapat melakukan trigger change
            // name.val($(this).attr('name')).trigger('change');
            optionList.addClass('hide');
            optionList.removeClass('blueFocusBorder');
            selectBox.removeClass('blueFocusBorder');
        });
    })

    // fungsi untuk menghilangkan optionLIst ketika mouse click diluar dari optionList
    $(document).mouseup(function(e){
        // jika target bukan variabel selectbox dan option list tidak memiliki class hide
        if((!selectBox.is(e.target) && !selectText.is(e.target)) && !optionList.hasClass('hide')){
            optionList.addClass('hide');
            optionList.removeClass('blueFocusBorder');
            selectBox.removeClass('blueFocusBorder');
            dropdownArrow.toggleClass('rotate');
        }
    });
}

function showTree(parentNode, status, indent){
    for(node in parentNode){
        // menyimpan value dari foreach
        value = parentNode[node]

        // untuk root node yang tidak memiliki child (tidak memiliki child namun bukan child)
        if(status == 'noChild' && value.children.length == 0){
            // menambahkan elemen ke class navigation tree
            $('.navigationTree').append("<div class='node noChild' style='margin-left: "+indent+"px;' id='"+value.id_node+"'> <span>"+value.nama_node+"</span> <a class='btnPilihJawaban'>Pilih Jawaban</a></div>")
        }

        // untuk setiap root node yang memiliki children
        if((status == "noChild" && value.children.length != 0)){
            // menambahkan elemen ke class navigation tree
            $('.navigationTree').append("<div class='collapseHeader node hasChild' style='margin-left: "+indent+"px;' id='"+value.id_node+"'> <img src='https://treet.site/img/dropdownArrow.svg' class='arrowClosed'></img><span>"+value.nama_node+"</span></div>");

            // membangun div yang memiliki id sesuai dengan id_node (div ini digunakan untuk collapsible yang mengandung child dari id ini)
            $('.navigationTree').append("<div class='collapsible hide' id='collapsibleid"+value.id_node+"'></div>");
        }

        // untuk setiap node yang bukan root(memiliki parent) namun memiliki child
        if(status == "hasChild" && value.children.length != 0){             
            // menambahkan elemen ke collapsible yang telah oleh parent dibuat sebelumnya
            $("<div class='collapseHeader node hasChild' style='margin-left: "+indent+"px;' id='"+value.id_node+"'> <img src='https://treet.site/img/dropdownArrow.svg' class='arrowClosed'></img><span>"+value.nama_node+"</span></div>").appendTo('#collapsibleid'+value.parent_node+'');

            // membangun div collapsible untuk child dari node ini
            $('#collapsibleid'+value.parent_node+'').append("<div class='collapsible hide' id='collapsibleid"+value.id_node+"'></div>");

        }

         // untuk setiap leaf node
        if((status == "hasChild" && value.children.length == 0)){
            // menambahkan elemen ini ke collapsible parent
            $("<div class='node noChild' style='margin-left: "+indent+"px;' id='"+value.id_node+"'><span>"+value.nama_node+"</span> <a class='btnPilihJawaban'>Pilih Jawaban</a></div>").appendTo('#collapsibleid'+value.parent_node+'')
            
        }
    
        // fungsi recursive jika node ini memiliki child
        if(value.children){
            showTree(value.children,"hasChild",indent+20);
        }
    }
}

// fungsi agar tree dapat collapsible
function treeCollapsible(btnHover = true){
    // agar jika node di klik, maka dapat collapse
    $(".collapseHeader").click(function(){
        // mengambil atribut id dari node yang di klik
        id = $(this).attr("id");
        img = $(this).find("img");

        // melakukan toggle class hide pada id, agar dapat di expand maupun collapse
        if(img.hasClass('arrowClosed')){
            $("#collapsibleid"+id).slideDown();
            img.css('transform','rotate(0deg)');
            img.removeClass('arrowClosed');
        }else{
            $("#collapsibleid"+id).slideUp();
            img.css('transform','rotate(-90deg)');
            img.addClass('arrowClosed');
        }
    });

    if(btnHover){
        // untuk menampilkan button jawaban saya jika node di hover
        $(".noChild").hover(function(){
            $(this).find("a").css("visibility","visible");
            
        },function(){
            $(this).find("a").css("visibility","hidden"); 
        });
    }
}

// fungsi dari pengujian tree testing
function fungsiPengujian(parentNode, task, percobaan = false){
    // untuk menyimpan jawaban partisipan
    // jika telah ada jawaban di session, maka jawaban dari session akan ditambahkan (tidak ditimpa) 
    if(sessionJawaban == null){
        jawaban = [];
    }else{
        jawaban = sessionJawaban;
    }
    // untuk menyimpan path yang diambil
    path = [];

    // counter untuk menentukan soal keberapa
    // kondisi agar jika partisipan telah mengerjakan beberapa soal, kemudian page di refresh maka partisipan dapat melanjutkan soal tersebut, tanpa perlu mengulang dari awal
    if(sessionJawaban != null && sessionJawaban.length > 0){
        count = sessionJawaban.length;
    }else{
        count = 0;
    }
    
    // i sebagai counter dari timer
    i = 1;
    //inisialisasi timer menggunakan set interval 
    timer = setInterval(function(){
        $("#timer").text(i++)
    },1000)

    // menentukan maksimum counter sesuai dengan jumlah task
    maxCount = task.length;
    // variable yang menyimpan segment progress dihitung dengan rumus 100% dibagi dengan jumlah task
    progressSegment = 100/maxCount

    // jika progress ada di session, maka progress bar akan melanjutkan progress dari session
    if(localStorage.getItem('progress') == null){
        // variable untuk menentukan lebar dari progress bar
        progress = progressSegment;
    }else{
        progress = localStorage.getItem('progress');
    }


    $("#progressSoal").css('width',progress+'%')

    // memanggil fungsi untuk menampilkan tree 
    showTree(parentNode, 'noChild', 0)
    // memanggil fungsi agar tree dapat collapsible
    treeCollapsible();
    
    // mengambil id ketika collapsible diklik
    $(".hasChild").click(function(){
        // memasukkan id tersebut ke array path
        path.push($(this).attr("id"));
    });

    // load soal/task pertama
    soal(task, count);

    // me-load soal dari variabel soal
    function soal(task, i){
        // variabel no soal
        taskNo = i+1;
        $(".soal h2").text("Task "+ taskNo +" dari "+maxCount);
        $(".soal p").text(task[i]['deskripsi']);
    }

    function nextTask(isiJawaban, lastNodeId = null){
        // konfirmasi jika button jawaban saya diklik
        if(confirm("Apakah anda yakin ?")){
            // untuk menampilkan preloader
            $('.preloader').css('visibility', 'visible');
            // hide preloader setelah .3 detik
            setTimeout(() => $('.preloader').css('visibility', 'hidden'), 300);

            // memasukkan id node terakhir pada path
            path.push(lastNodeId);
            // menyimpan jawaban ke array jawaban yang berisi id_task, id_jawaban, dan path taken
            jawaban.push(isiJawaban);
            // mengosongkan path agar pada task selanjutnya jawaban tidak tertimpa
            path = [];
            // melakukan incremen, untuk memulai soal selanjutnya
            count ++;

            // menyimpan setiap collapsible yang memiliki id collapsibleid* menggunakan regex (* adalah id)
            collapsibleId = $(".collapsible[id^='collapsible']");

            // if untuk menutup setiap collapsible jika tombol di next
            if($(".hasChild img").hasClass('arrowClosed')){
                collapsibleId.slideUp();
                $(".hasChild img").css('transform','rotate(-90deg)');
                $(".hasChild img").addClass('arrowClosed');
            }

            // update perhitungan progress bar
            progress = parseInt(progress) + progressSegment
            $("#progressSoal").css('width',progress+'%')

            // menyimpan jawaban di local storage
            localStorage.setItem('jawaban', JSON.stringify(jawaban));

            // menyimpan progress dari progress bar
            localStorage.setItem('progress', progress);

            console.log(JSON.parse(localStorage.getItem('jawaban')));
            // melakukan load soal selanjutnya atau keluar halaman pengujian
            if(count < maxCount){
                soal(task, count)
                // menutup kembali collapsible yang dibuka
                $(".collapsible").css("display","none");
                // melakukan scroll keatas
                window.scrollTo({ top : 0, behavior : 'smooth' });
            }else{
                // jika bukan percobaan, maka jawaban akan disimpan ke database
                if(!percobaan){
                    // setup header untuk mengirimkan csrf token yang diambil dari tag meta diatas
                    $.ajaxSetup({
                        headers : {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        }
                    });
    
                    // menggunakan ajax request untuk menyimpan jawaban ke database
                    $.ajax({
                        url : "/partisipan/storeJawaban",
                        type: "post",
                        data : localStorage.getItem('jawaban'),
                        datatype: "json",
                        contentType: "application/json",
                        processData: false,
                        success : function(res){
                            console.log(res);
                            localStorage.clear();
                            $('.preloader').css('visibility','visible');
                            window.location.href = "/partisipan/selesaiPengujian";
                        },
                        error : function(request, status, error){
                            console.log(request.responseText);
                        }
                    });
                }else{
                    // menghapus data di local storage
                    localStorage.clear();
                    // mengarahkan ke halaman selesai pengujian dengan mengoper id
                    window.location.href = "/partisipan/selesaiPengujian/1";
                }
            }
        }
    }

    // fungsi ketika jawaban diklik
    $(".navigationTree .btnPilihJawaban").on("click",function(){
        // mengambil id node terakhir
        lastNodeId = $(this).parent().attr("id"); 
        // memanggil function nextTask dengan parameter objek yang berisi id_task, id_jawaban dan path
        nextTask({id_task : task[count]['id_task'],
                id_node : $(this).parent().attr("id"),
                path : path,
                waktu : $("#timer").text() }, lastNodeId);
        // melakukan reset timer
        $("#timer").text("0");
        i = 0;
    });

    // jika tombol lewati pertanyaan di click
    $("#lewatiPertanyaan").on("click", function(){
        nextTask({id_task : task[count]['id_task'],
                id_node : null,
                path : null,
                waktu : null}, null);
        // melakukan reset timer
        $("#timer").text("0");
        i = 0;
    });
}

// fungsi untuk modal, buttonId = trigger modal ; modalId = container modal
function openCloseModal(buttonId, modalId){
    $(buttonId).click(function(){
        $(modalId).fadeIn()
    });

    $(".closeModal").click(function(){
        $(modalId).fadeOut()
    });
    
    $(".modalBatal").click(function(){
        $(modalId).fadeOut()
    });

    // untuk menghilangkan modal ketika user klik diluar modal
    $(window).click(function(e){
        if(e.target.id == $(modalId).attr("id")){
            $(modalId).fadeOut();
        }
    });
};

// mengambil object tanggal dan merubah ke string
function getStringDate(date = false){
    if(!date){
        // mengambil tanggal hari ini
        var date = new Date();
    }
    
    // mengambil hari dalam bentuk angka
    var hari = date.getDate();
    // mengambil bulan dalam bentuk angka ditambah 1 karena dalam js bulan dimulai dari 0
    var bulan = date.getMonth() + 1;
    // mengambil tahun
    var tahun = date.getFullYear();

    // untuk hari dan bulan yang kurang dari 10 akan ditambahkan string 0 didepannya
    if(hari < 10 ){
        hari = "0" + hari.toString();
    }
    if(bulan < 10){
        bulan = "0" + bulan.toString();
    }

    // menggabungkan tanggal yang telah diambil sebelumnya
    tanggal = tahun.toString() + '-' + bulan + '-' + hari;
    return tanggal;
}

// fungsi untuk menghitung data untuk boxplot
function getBoxPlotData(array){
    // exclude nilai null pada array
    array = array.filter(x => ![null].includes(x));
    // mengurutkan array
    array = array.sort((a,b)=>a-b)
    // menghitung panjang array
    count = array.length
    // panjang array dikurang 1 agar menjadi key
    key = count-1
    // jumlah array dibagi 2 lalu dibulatkan
    tengah = Math.round(key/2)
    
    // === Menghitung Q1, Q2, Q3 ===
    median = 0;
    q1 = 0;
    q3 = 0;
    iqr = 0;

    // Rumus Median
    if(count % 2 == 1){
        // rumus jika jumlah array ganjil
        // membagi array untuk menghitung q1 dan q3
        arrayQ1 = array.slice(0, tengah)
        arrayQ3 = array.slice(tengah+1)

        median = array[tengah];
    }else{
        // membagi array untuk menghitung q1 dan q3
        arrayQ1 = array.slice(0, tengah)
        arrayQ3 = array.slice(tengah)
        // rumus jika jumlah array genap
        median = (array[tengah-1] + array[tengah]) / 2
    }
    
    if(count != 1){
        // rumus q1
        if(arrayQ1.length % 2 == 1){
            tengah = Math.round(arrayQ1.length/2)
            // rumus jika jumlah array ganjil
            q1 = arrayQ1[tengah-1];         
        }else{
            tengah = Math.round(arrayQ1.length/2)
            // rumus jika jumlah array genap
            q1 = (arrayQ1[tengah-1] + arrayQ1[tengah]) / 2
        }
    
        // rumus q3
        if(arrayQ3.length % 2 == 1){
            tengah = Math.round(arrayQ3.length/2)
            // rumus jika jumlah array ganjil
            q3 = arrayQ3[tengah-1];         
        }else{
            tengah = Math.round(arrayQ3.length/2)
            // rumus jika jumlah array genap
            q3 = (arrayQ3[tengah-1] + arrayQ3[tengah]) / 2
        }

        iqr = q3-q1;
    }else{
        q1 = array[0];
        q3 = array[0];
    }

    // === Menghitung Rata-rata ===
    sum = 0;

    array.forEach(function(value, i){
        sum = sum + value;
    });
    
    average = sum / count;
    
    // === Mengambil nilai maksimum dan minimum ===
    minimum = Math.min.apply(Math, array)
    maksimum = Math.max.apply(Math, array)

    // === Memasukkan semua hasil ke object ===
    hasil = {
        "minimum": minimum,
        "maksimum": maksimum,
        "average": average,
        "q1": q1,
        "median": median,
        "q3": q3,
        "iqr": iqr
    }

    return hasil;
}

// handler konfirmasi
$(document).on('click', 'a.konfirmasi', function(e){
    e.preventDefault();
    pesan = $(this).attr('data-pesan');
    href = $(this).attr('href');

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
            window.location.href = href;
        }
    });
});