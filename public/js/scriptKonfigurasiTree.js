// memanggil fungsi showKonfigurasiTree untuk menampilkan konfigurasi tree
showKonfigurasiTree(parentNode, "noChild", 0)
// memanggil fungsi tree collapsible, dengan parameter btnHover = false
treeCollapsible(false);

// menampilkan tree konfigurasi
function showKonfigurasiTree(parentNode, status, indent){
    for(node in parentNode){
        // menyimpan value dari foreach
        value = parentNode[node]
        // boolean untuk cek apakah div between pertama atau bukan
        firstBetween = "false";
        // mengambil previous node
        if(parentNode[+node-1]){
            prevId = parentNode[+node-1].id_node
        }else{
            prevId = parentNode[+node].id_node
            firstBetween = "true";
        }
        nextId = null;
        // indentasi untuk input
        inputIndent = indent + 15;
    
        // untuk setiap root node 
        if(status == 'noChild' && value.children.length == 0){
            // menambahkan elemen ke class navigation tree
            $('.bodyKonfigurasiTree').append("<div class='between' data-firstBetween='"+firstBetween+"' data-lowerId='"+prevId+"' style='margin-left: "+indent+"px;'><hr></div>"+"<div class='node nodeKonfigurasi noChild draggable' style='margin-left: "+indent+"px;' draggable='true'>"+
                "<span>"+value.nama_node+"</span>"+
                "<div class='nodeButton'>"+
                    "<a href='/tester/hapusTree/"+value.id_node+"' class='btn btnRed konfirmasi' data-pesan='Node akan dihapus' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                    "<a class='btn btnOrange btnModalEditNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconEdit.svg'></a>"
                    +
                    "<a class='btn btnGreen showFieldAddNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                    "<a class='btn btnDarkBlue btnModalSetJawaban' id='"+value.id_node+"'><img src='https://treet.site/img/iconSetJawaban.svg'>Set Jawaban</a>"+
                    "&nbsp; &nbsp;<input type='checkbox' class='checkboxTree' name='ids[]' value='"+value.id_node+"'>"+
                "</div>"+
            "</div>"+
            "<input type='text' style='margin-left: "+inputIndent+"px;' class='addNode addNodeId"+value.id_node+"' id='"+value.id_node+"' placeholder='Tambah child dari node "+value.nama_node+"'>"+
            "<div class='between' data-lowerid='"+value.id_node+"' style='margin-left: "+indent+"px;'></div>");
        }

        // untuk setiap root node yang memiliki children
        if((status == "noChild" && value.children.length != 0)){
            // menambahkan elemen ke class navigation tree
            $('.bodyKonfigurasiTree').append("<div class='between' data-firstBetween='"+firstBetween+"' data-lowerId='"+prevId+"' style='margin-left: "+indent+"px;'></div>"+"<div class='collapseHeader node nodeKonfigurasi hasChild draggable' style='margin-left: "+indent+"px;' id='"+value.id_node+"' draggable='true'>"+
                "<img src='https://treet.site/img/dropdownArrow.svg' class='arrowClosed'>"+
                "<span>"+value.nama_node+"</span>"+
                "<div class='nodeButton nodeButtonCollapsible'>"+
                    "<a href='/tester/hapusTree/"+value.id_node+"' class='btn btnRed konfirmasi' data-pesan='Anak dari node ini juga akan dihapus' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                    "<a class='btn btnOrange btnModalEditNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconEdit.svg'></a>"+
                    "<a class='btn btnGreen showFieldAddNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                    "&nbsp; &nbsp;<input type='checkbox' class='checkboxTree checkboxHasChild' name='ids[]' value='"+value.id_node+"'>"+
                "</div>"+
            "</div>"+
            "<div class='between' data-lowerid='"+value.id_node+"' style='margin-left: "+indent+"px;'></div>");

            // membangun div yang memiliki id sesuai dengan id_node (div ini digunakan untuk collapsible yang mengandung child dari id ini)
            $('.bodyKonfigurasiTree').append("<div class='collapsible hide' id='collapsibleid"+value.id_node+"'></div>"+
            "<input type='text' style='margin-left: "+inputIndent+"px;' class='addNode addNodeId"+value.id_node+"' id='"+value.id_node+"' placeholder='Tambah child dari node "+value.nama_node+"'>");
        }

        // untuk setiap node biasa yang memiliki child
        if(status == "hasChild" && value.children.length != 0){             
            // menambahkan elemen ke collapsible yang telah oleh parent dibuat sebelumnya
            $("<div class='between' data-firstBetween='"+firstBetween+"' data-betweenParentId='"+value.parent_node+"' data-lowerId='"+prevId+"' style='margin-left: "+indent+"px;'></div>"+"<div class='collapseHeader node nodeKonfigurasi hasChild draggable' style='margin-left: "+indent+"px;' id='"+value.id_node+"' data-draggableParentId='"+value.parent_node+" draggable='true'>"+
                "<img src='https://treet.site/img/dropdownArrow.svg' class='arrowClosed'>"+
                "<span>"+value.nama_node+"</span>"+
                "<div class='nodeButton nodeButtonCollapsible'>"+
                    "<a href='/tester/hapusTree/"+value.id_node+"' class='btn btnRed konfirmasi' data-pesan='Anak dari node ini juga akan dihapus' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                    "<a class='btn btnOrange btnModalEditNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconEdit.svg'></a>"+
                    "<a class='btn btnGreen showFieldAddNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                    "&nbsp; &nbsp;<input type='checkbox' class='checkboxTree checkboxHasChild' name='ids[]' data-checkboxParent='"+value.parent_node+"' value='"+value.id_node+"'>"+
                "</div>"+
            "</div>"+
            "<div class='between' data-betweenParentId='"+value.parent_node+"' data-lowerid='"+value.id_node+"' style='margin-left: "+indent+"px;'></div>").appendTo('#collapsibleid'+value.parent_node+'');

            // membangun div collapsible untuk child dari node ini
            $('#collapsibleid'+value.parent_node+'').append("<div class='collapsible hide' id='collapsibleid"+value.id_node+"'></div>"+
            "<input type='text' style='margin-left: "+inputIndent+"px;' class='addNode addNodeId"+value.id_node+"' id='"+value.id_node+"' placeholder='Tambah child dari node "+value.nama_node+"'>");

        }

        // untuk setiap leaf node
        if((status == "hasChild" && value.children.length == 0)){
            // menambahkan elemen ini ke collapsible parent
            $("<div class='between' data-firstBetween='"+firstBetween+"' data-betweenParentId='"+value.parent_node+"' data-lowerId='"+prevId+"' style='margin-left: "+indent+"px;'></div>"+"<div class='node nodeKonfigurasi noChild draggable' style='margin-left: "+indent+"px;' draggable='true' data-draggableParentId='"+value.parent_node+"'>"+
                    "<span>"+value.nama_node+"</span>"+
                    "<div class='nodeButton'>"+
                        "<a href='/tester/hapusTree/"+value.id_node+"' class='btn btnRed konfirmasi' data-pesan='Node akan dihapus' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                        "<a class='btn btnOrange btnModalEditNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconEdit.svg'></a>"+
                        "<a class='btn btnGreen showFieldAddNode' id='"+value.id_node+"'><img src='https://treet.site/img/iconDeleteBundar.svg'></a>"+
                        "<a class='btn btnDarkBlue btnModalSetJawaban' id='"+value.id_node+"'><img src='https://treet.site/img/iconSetJawaban.svg'>Set Jawaban</a>"+
                        "&nbsp; &nbsp;<input type='checkbox' class='checkboxTree' name='ids[]' data-checkboxParent='"+value.parent_node+"' value='"+value.id_node+"'>"+
                    "</div>"+
                "</div>"+
                "<input type='text' style='margin-left: "+inputIndent+"px;' class='addNode addNodeId"+value.id_node+"' id='"+value.id_node+"' placeholder='Tambah child dari node "+value.nama_node+"'>"+
                "<div class='between' data-betweenParentId='"+value.parent_node+"' data-lowerid='"+value.id_node+"' style='margin-left: "+indent+"px;'></div>").appendTo('#collapsibleid'+value.parent_node+'')
            
        }
    
        // fungsi recursive jika node ini memiliki child
        if(value.children){
            showKonfigurasiTree(value.children,"hasChild",indent+20);
        }
    }
}

// untuk setiap checkbox tree
$(".checkboxTree").click(function(e){
    // agar tidak trigger collapsible ketika checkbox diklik
    e.stopPropagation();
    // menyimpan checkbox parent
    checkboxParent = $(this).attr('data-checkboxParent');

    // jika ada checkbox parent
    if(checkboxParent){
        // jika semua checkbox pada parent yang sama di check, namun checkbox ini tidak dicek, maka checkbox parent juga tidak akan di check
        if(!$(this).prop('checked')){
            $(`.checkboxHasChild[value=${checkboxParent}]`).prop('checked', false);
        }
    }
});

// jika tulisah pilih semua di klik, maka akan mengganti checkbox checkall
$("#boxPilihSemua span").click(function(){
    if($("#checkAll").prop('checked')){
        $("#checkAll").prop('checked', false);
    }else{
        $("#checkAll").prop('checked', true);
    }

    if($("#checkAll").is(":checked")){
        $(".checkboxTree").prop('checked', true);
    }else{
        $(".checkboxTree").prop('checked', false);
    }
});

// untuk setiap checkbox yang memiliki child
$(".checkboxHasChild").click(function(){
    id = $(this).attr('value');
    if($(this).is(":checked")){
        $(`.checkboxTree[data-checkboxParent=${id}]`).prop('checked', true);
        
    }else{
        $(`.checkboxTree[data-checkboxParent=${id}]`).prop('checked', false);
    }
});

// fungsi jika checkbox pilih semua diklik
$("#checkAll").click(function(){
    if($(this).is(":checked")){
        $(".checkboxTree").prop('checked', true);
    }else{
        $(".checkboxTree").prop('checked', false);
    }
});

// untuk melakukan submit form delete jika tombol di klik
$("#submitFormDeleteNode").click(function(){
    Swal.fire({
        title: 'Apakah Anda Yakin ?',
        text: "Semua node yang dipilih akan dihapus !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        if (result.isConfirmed) {
            $("#formDeleteNode").submit();
        }
    });
});

// fungsi untuk menampilkan field add node
$(".showFieldAddNode").click(function(e){
    // untuk menghentikan onclick event milik parent(jika ada)
    e.stopPropagation();
    $(".addNodeId"+$(this).attr('id')).slideToggle(400);
});

// fungsi untuk menambahkan node tree jika text field addNode ditekan
$(".addNode").keypress(function(e){
    // fungsi untuk menentukan jika tombol enter ditekan
    if(e.which == 13){
        // agar form delete pilihan tidak ditrigger
        e.preventDefault();
        // mengambil nama node dari inner html dan parent node dari attribut id
        nama_node = $(this).val();
        parent_node = $(this).attr("id");

        // jika field tidak kosong
        if(nama_node != ""){
            // mengirimkan csrf token
            $.ajaxSetup({
                headers : {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });

            // fungsi ajax untuk menambah node secara asynchronous
            $.ajax({
                url: "/tester/tambahNode",
                method: "POST",
                data: {
                    nama_node : nama_node,
                    parent_node : parent_node
                },
                success: function(res){  
                    if(res.includes('<!DOCTYPE html>')){
                        console.log('yes')
                        Swal.fire({
                            icon: 'error',
                            title: 'Error !',
                            text: 'Tidak dapat melakukan perubahan pada tree/node ketika pengujian sedang dilaksanakan atau terdapat hasil pada halaman hasil pengujian !',
                          })
                    }else{
                        location.reload();
                    }
                },
                error : function(request, status, error){
                    console.log(request.responseText);
                    location.reload();
                }
            });
        }
    }
});

// ============== Modal ================

// fungsi modal Tambah Task
openCloseModal("#btnModalTambahTask", "#modalTambahTask");        
// fungsi modal Edit task (menggunakan class karena ada banyak modal edit task)
openCloseModal(".btnModalEditTask", "#modalEditTask");        
// fungsi modal set jawaban
openCloseModal(".btnModalSetJawaban", "#modalSetJawaban");        
// fungsi modal edit node
openCloseModal(".btnModalEditNode", "#modalEditNode")
// fungsi modal edit informasi pengujian
openCloseModal("#btnModalEditInformasi", "#modalEditInformasi")

// fungsi untuk mengisi value ketika tombol edit diketik
$(".btnModalEditTask").click(function(){
    // mengambil input type hidden
    inputId = ("#editJawaban");
    // mengambil value dari kolom deskripsi pada table
    deskripsi = $(this).parent().parent().children().eq(1).text();
    // mengambil value dari kolom kriteria pada table
    kriteria = $(this).parent().parent().children().eq(2).text();
    // mengambil name dari kolom jawaban pada table yang berisi id node
    id_node = $(this).parent().parent().children().eq(3).attr('name');
    // mengambil value dari kolom jawaban pada table
    jawaban = $(this).parent().parent().children().eq(3).text();
    // mengambil value dari kolom id task pada table yang di hidden
    id_task = $(this).parent().parent().children().eq(4).text();            
    // mengambil value dari kolom direct path pada table yang di hidden
    direct_path = $(this).parent().parent().children().eq(5).text();            

    // mengisi value pada modal sesuai dengan kolom edit yang diklik
    // value field deskripsi
    $("#modalEditTask textarea").text(deskripsi);

    // value selectbox jawaban
    if(jawaban != "Jawaban belum dipilih"){
        $("#selectEditJawaban p").text(jawaban);
    }else{
        $("#selectEditJawaban p").text("Pilih Jawaban Task Ini");
    }

    // value selectbox kriteria
    if(kriteria != ""){
        $("#selectEditKriteria p").text(kriteria);
    }else{
        $("#selectEditKriteria p").text("Pilih Kriteria Dari Task Ini");
    }
    // value input type hidden jawaban (diisi dengan id_node)
    $("#editJawaban").attr('value',id_node);
    $("#editKriteria").attr('value',kriteria);
    // value input type hidden id_task
    $("#id_task").attr('value',id_task);
    $("#editDirectPath").attr('value',direct_path);

});

// Fungsi modul set jawaban
$(".btnModalSetJawaban").click(function(){
    // mengisi input type hidden pada modal set jawaban dengan id_node
    $("#id_node").attr("value",$(this).attr("id"))
    
    // mengambil collapsible id
    parent = $(this).parent().parent().parent().attr("id");
    directPath = [$(this).attr("id")];
    loop = true

    if(parent){
        while(loop){
            getNumber = parent.match(/\d+/);
            parentId = getNumber[0];
            directPath.push(parentId)
            parent = $("#collapsibleid"+parentId).parent().attr("id");
            if(!parent){
                loop = false;
            }
        }
    }
    // memutar balik array direct path
    directPath = directPath.reverse()
    // mengisi value pada input tpye hidden name directPath
    $("#pathSetJawaban").attr("value", directPath.join('->'))
});

// fungsi untuk modal edit node 
$(".btnModalEditNode").click(function(e){
    e.stopPropagation();
    // mengambil nama node lama
    namaNode = $(this).parent().parent().children('span').text();

    // set nama node ke input type value
    $("#nama_node").attr("value", namaNode);

    // mengisi input type hidden pada modal edit node dengan id node
    $("#idEditNode").attr("value",$(this).attr("id"))
});

function dragAndDrop(){
    // untuk menyimpan id node yang di drag
    draggedId = null;
    // menyimpan draggable parent
    draggedParent = null;

    // ===== Fungsi drag drop setiap node ======
    function dragStart(e){
        // mengambil id node yang sedang di drag
        draggedId = $(this).find('.btnOrange').attr('id');
        // mengambil parent dari draggable (jika ada)
        draggedParent = $(this).attr('data-draggableParentId');
    }
    
    function dragEnter(){
        // menghapus style hover ketika drag masuk ke node
        this.classList.add('dragHover');
    }

    function dragLeave(){
        // menghapus style hover ketika drag pergi dari node
        this.classList.remove('dragHover');
    }

    function dragOver(e){
        // mengentikan fungsi otomatis dari dragover
        e.preventDefault();
    }
    
    // fungsi drop untuk node
    function dragDrop(e){
        // menghapus hover style
        this.classList.remove('dragHover');
        // mengambil id dari node yang akan dijadikan parent
        parent = $(this).find('.btnRed').attr('id');

        // jika parent tidak sama dengan id node yg sedang di drag
        if(parent && parent != draggedId){
            $.ajaxSetup({
                headers : {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            // fungsi ajax untuk mengganti parent node secara asynchronous
            $.ajax({
                url: "/tester/editParentNode",
                method: "POST",
                data: {
                    id_node : draggedId,
                    parent_node : parent
                },
                success: function(res){  
                    if(res.includes('<!DOCTYPE html>')){
                        console.log('yes')
                        Swal.fire({
                            icon: 'error',
                            title: 'Error !',
                            text: 'Tidak dapat melakukan perubahan pada tree/node ketika pengujian sedang dilaksanakan atau terdapat hasil pada halaman hasil pengujian !',
                          })
                    }else{
                        location.reload();
                    }
                },
                error : function(request, status, error){
                    console.log(request.responseText);
                    location.reload();
                }
            });
        }
    }

    // ====== fungsi drag drop untuk div between =======
    function dragEnterBetween(){
        this.classList.add('betweenHover');
    }

    function dragLeaveBetween(){
        this.classList.remove('betweenHover');
    }

    function dragDropBetween(){
        this.classList.remove('betweenHover');
        // mengambil parent dari div between yang berada diantara setiap node
        parent = $(this).attr('data-betweenParentId');
        // mengambil id, node diatas div between ini
        lowerId = $(this).attr('data-lowerId');
        first = $(this).attr('data-firstBetween');
        console.log(draggedParent);
        // jika tidak ada parent
        if(!parent){
            parent = null;
        }

        if(draggedId != lowerId){
            $.ajaxSetup({
                headers : {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            // fungsi ajax untuk mengganti parent node secara asynchronous
            $.ajax({
                url: "/tester/editParentNode",
                method: "POST",
                data: {
                    id_node : draggedId,
                    parent_node : parent,
                    swappedNode : lowerId,
                    firstBetween : first,
                    draggedParent : draggedParent
                },
                success: function(res){  
                    if(res.includes('<!DOCTYPE html>')){
                        console.log('yes')
                        Swal.fire({
                            icon: 'error',
                            title: 'Error !',
                            text: 'Tidak dapat melakukan perubahan pada tree/node ketika pengujian sedang dilaksanakan atau terdapat hasil pada halaman hasil pengujian !',
                          })
                    }else{
                        console.log(res)
                        location.reload();
                    }
                },
                error : function(request, status, error){
                    console.log(request.responseText);
                    location.reload();
                }
            });
        }
    }

    // mengambil setiap class draggable
    draggables = document.querySelectorAll('.draggable');
    // mengambil setiap class node
    nodes = document.querySelectorAll('.bodyKonfigurasiTree .node');
    betweens = document.querySelectorAll(('.between'));
    bodyKonfigurasiTree = document.querySelector('.bodyKonfigurasiTree');

    // menambahkan event untuk setiap class draggable
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', dragStart);
    });

    // menambahkan event untuk setiap node pada tree
    nodes.forEach(node => {
        node.addEventListener('dragover', dragOver);
        node.addEventListener('dragenter', dragEnter);
        node.addEventListener('dragleave', dragLeave);
        node.addEventListener('drop', dragDrop);
    });
    
    betweens.forEach(between => {
        between.addEventListener('dragover', dragOver);
        between.addEventListener('dragenter', dragEnterBetween);
        between.addEventListener('dragleave', dragLeaveBetween);
        between.addEventListener('drop', dragDropBetween);
    })

}

// menjalankan fungsi drag and drop
dragAndDrop();

// ========= SelectBox ==============

// selectbox field jawaban modal tambah task
customSelectbox('#jawaban','#selectJawaban','#optionListJawaban','.optionsJawaban');
// selectbox field edit jawaban
customSelectbox('#editJawaban','#selectEditJawaban','#optionListEditJawaban','.optionsEditJawaban');
// selectbox field set jawaban
customSelectbox('#setJawaban','#selectSetJawaban','#optionListSetJawaban','.optionsSetJawaban');
// selectbox field kriteria
customSelectbox('#kriteria','#selectKriteria','#optionListKriteria','.optionsKriteria');
// selectbox field edit kriteria
customSelectbox('#editKriteria','#selectEditKriteria','#optionListEditKriteria','.optionsEditKriteria');
// selectbox field atribut
customSelectbox('#atribut','#selectAtribut','#optionListAtribut','.optionsAtribut');