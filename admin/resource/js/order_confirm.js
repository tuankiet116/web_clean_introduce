var base_url = '../../../';
var order_id_g = null;
$(document).ready(function () {
    $('#order_reason').niceSelect();
    $(".pick_website_select").select2({
        ajax: { 
          url: "../../../api/Controller/searchTerm.php",
          type: "POST",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            if(params.term == null){
              var obj = {
                "term": ""
              } 
            }else{
              var obj = {
              "term": params.term.trim()
              } 
            }
            
            return JSON.stringify(obj);
          },
          processResults: function (data, params) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.web_name,
                        id: item.web_id,
                        image: checkdefault("data/web_icon/icon_default/default.png",item.web_icon),
                        description: item.web_description,
                        data: item
                    };
                })
            };
          },
          cache: false
        },
        placeholder: 'Search for a Website',
        minimumInputLength: 0,
        templateResult: formatRepoWebsite,
        templateSelection: formatRepoSelectionWebsite
    });

    $(".pick_website_select").change(function(){
        let web_id = $(this).select2('val');
        getOrder(true, web_id, "");
        $('#order_search').val("")
    })
    getOrder(false, null, "");
    searchTerm();
    clearTerm();
});

function getOrder(web_id = false, valueWebSite=null, term){
    let data = {
        "term": term,
        "order_status": "1"
    };
    let urlApi = "getAllOrder";
    if(web_id == true && valueWebSite != null){
        data={
            "web_id": valueWebSite,
            "term": term,
            "order_status": "1"
        }
        urlApi = "getOrderByWebId";
    }
    $.ajax({
        type: "POST",
        url: base_url+"api/Controller/"+urlApi+".php",
        data: JSON.stringify(data),
        dataType: "JSON",
        async: false,
        success: function (res) {
            // console.log(res);
            if(res.code == 403){
                window.location.href = '../../error.php';
            }
            else{
                if(res.code == 200){
                    var viewData = res?.result.map(function(item, index){
                        let order_status = item.order_status == 1? `<p style="color: red">Chưa Xác Nhận <i class="fas fa-times"></i></p>`: ``;
                        let order_payment = '';
                        let notSp = `Null`
                        let order_suspicious = '';
                        // let order_refund_code = null;

                        if(item.order_payment == 1){
                            order_payment ='<span class="badge badge-secondary">COD</span>';
                        }
                        else if(item.order_payment == 2){
                            order_payment ='<span style="background-color: magenta !important;" class="badge badge-danger">MOMO</span>';
                        }
                        else{
                            order_payment ='<span class="badge badge-secondary">Khác</span>';
                        }
    
                        if(item.order_suspicious == 1 && item.order_payment == 2){
                            order_suspicious = `<span class="badge badge-danger" style="display: block; width: 100px">Giao Dịch Khả Nghi</span>`;
                        }

                        let order_payment_status = item.order_payment_status == 0 ? `<span class="badge badge-success">Đã Thanh Toán</span>`:`<span class="badge badge-danger">Chưa Thanh Toán</span>`

                        // if(item.order_refund_code == 0){
                        //     order_refund_code = `<p>Đã Hoàn Tiền</p>`;
                        // }
                    
                        // if(item.order_refund_code != 0 && item.order_refund_code != null){
                        //     order_refund_code = `<p>Chưa Hoàn Tiền</p> <span>(${item.order_refund_message})</span>`;
                        // }

                        // if(item.order_refund_code == null){
                        //     order_refund_code = '<p style="color: red">N/A</p>';
                        // }
                        
                        if(item.order_trans_id == null){
                            item.order_trans_id = '<p style="color: red">N/A</p>';
                        }
                        
                        if(item.user_name == null){
                            item.user_name = '<p style="color: red">N/A</p>';
                        }
                        return`
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td><p class="order_code">${item.order_id}</p> ${order_suspicious}</td>
                                <td>${item.user_name}</td>
                                <td>${item.user_number_phone}</td>
                                <td>${order_payment} ${order_payment_status}</td>
                                <td>${item.order_trans_id ?? notSp}</td>
                                <td>${item.web_name}</td>
                                <td>${item.order_sum_price}</td>
                                <td>${order_status}</td>
                                <td >
                                    <button class="btn btn-primary btn-detail" style="display: inline-block" order_id="${item.order_id}" data-toggle="modal" data-target="#show-modal-detail">Chi Tiết</button>
                                    <button class="btn btn-danger btn-cancel" order_id="${item.order_id}" data-toggle="modal" data-target="#modal-cancel">Hủy Bỏ</button>
                                </td>
                            </tr>
                        `
                    });
                }
                else{
                    var mes = `<tr style="background-color: white;">
                                 <td colspan="11"><p style="color:red; text-align: center">${res?.message}</p></td>
                               </tr>`; 
                }
                $('.table > tbody').html(viewData ?? mes).ready(function(){
                    getOrderById();
                    $('#btn-confirm').unbind().click(confirmed);
                    getOrderIdCancel();
                    $('#btn-cancel').unbind().click(cancel);
                    tooltip('.order_code', 10);
                });
            }
        }
    });
}

function getOrderById(){
    $('.btn-detail').unbind().click(function () { 
        let data = {
            "order_id": $(this).attr('order_id'),
            "order_status": "1"
        }
        order_id_g = $(this).attr('order_id');
        $.ajax({
            type: "POST",
            url: base_url+"api/Controller/getOrderById.php",
            data: JSON.stringify(data),
            dataType: "JSON",
            async:false,
            success: function (res) {
                console.log(res);
                if(res.code == 403){
                    window.location.href = '../../error.php';
                }
                else{
                    if(res.code == 200){
                        valueDetail(res)
                    }
                }
            },
            error: function(res){
                console.log(res.responeText);
            }
        });
        tooltip('#order_description', 30);
    });
    
}

function searchTerm(){
    $('#btn-search').unbind().click(function(){
        let term = $('#order_search').val();
        getOrder(false, null, term);
    })
}

function clearTerm(){
    $('#btn-clear').unbind().click(function(){
        $('.pick_website_select').empty();
        $('#order_search').val('');
        getOrder(false, null, "");
    })
}

function confirmed(){
    let data ={
        "order_id": order_id_g
    }
    $('.loader-container').css('display', 'flex');
    $.ajax({
        type: "POST",
        url: base_url+"api/Controller/orderConfirm.php",
        data: JSON.stringify(data),
        dataType: "JSON",
        async: false,
        success: function (res) {
            console.log(res);
            $('.loader-container').css('display', 'none');
            if(res.code == 403){
                window.location.href = '../../error.php'
            }
            else{
                if(res.code == 200){
                    showAlert('success', `<p>${res?.message}</p>`);
                    $('#close-modal-detail').click();
                    // order_id_g = null;
                }
                else {
                    showAlert('error', `<p>${res?.message}</p>`);
                }
            }
        },
        error: function(res){
            $('.loader-container').css('display', 'none');
            console.log(res.responeText);
        }
    });
    getOrder(false, null, "");
}

function getOrderIdCancel(){
    $('.btn-cancel').unbind().click(function(){
        order_id_g = $(this).attr('order_id');
    })
}

function cancel(){
    let data ={
        "order_id": order_id_g,
        "order_status": "3",
        "order_reason": $('#order_reason').val()
    }
    console.log(data);
    $('.loader-container').css('display', 'flex');
    $.ajax({
        type: "POST",
        url: base_url+"api/Controller/orderCancel.php",
        data: JSON.stringify(data),
        dataType: "JSON",
        async: false,
        success: function (res) {
            $('.loader-container').css('display', 'none');
            switch(res.code){
                case 403:
                    window.location.href= '../../error.php';
                    break;
                case 200:
                    showAlert("success", `<p>Hủy thành công</p>`);
                    $('#close_modal_detail').click();
                    break;
                case 500:
                    showAlert("error", `<p>Lỗi không thể hủy đơn lúc này</p>`);
                    break;
                case 404:
                    showAlert("error", `<p>Đơn hàng không tồn tại</p>`);
                    break;
                case 1002:
                    showAlert("error", `<p>Đơn hàng đã được xử lý. Vui lòng kiểm tra lại.</p>`);
                    break;
                default:
                    showAlert("error", `<p>Có lỗi xảy ra (default)</p>`);
                    break;
            }
        },
        error: function(res){
            showAlert("error", `<p>Có lỗi xảy ra (error)</p>`);
            console.log(res.responseText);
        }
    });
    getOrder(false, null, "");
}

function valueDetail(data){
    let order_payment = '';
    let order_status ='';
    let order_suspicious ='';
    if(data.result.order_payment == 1){
        order_payment ='<span class="badge badge-secondary">COD</span>';
    }
    else if(data.result.order_payment == 2){
        order_payment ='<span style="background-color: magenta !important;" class="badge badge-danger">MOMO</span>';
    }
    else{
        order_payment ='<span class="badge badge-secondary">Khác</span>';
    }

    if(data.result.order_status == "1"){
        order_status = 'Chưa Xác Nhận';
    }
    else if(data.result.order_status == "2"){
        order_status = 'Đã Xác Nhận';
    }

    if(data.result.order_suspicious == 1 && data.result.order_payment == 2){
        order_suspicious = `<span class="badge badge-danger">Giao dịch Khả Nghi</span>`;   
    }

    // if(data.result.order_refund_code == 0){
    //     order_refund_code = `Đã Hoàn Tiền`;
    // }

    // if(data.result.order_refund_code != 0 && data.result.order_refund_code != null){
    //     order_refund_code = `Chưa Hoàn Tiền (${data.result.order_refund_message})`;
    // }

    
    let order_payment_status = data.result.order_payment_status == 0 ? `<span class="badge badge-success">Đã Thanh Toán</span>`:`<span class="badge badge-danger">Chưa Thanh Toán</span>`
    let order_detail = data.result.order_detail.map(function(item){
        return `<tr>
                    <td style="border: 1px solid #dee2e6; padding: 5px"> ${item.product_name} </td>
                    <td style="border: 1px solid #dee2e6; padding: 5px"> ${item.order_detail_quantity} </td>
                    <td class="amount-number" style="border: 1px solid #dee2e6; padding: 5px"> ${item.order_detail_amount} </td>
                </tr>`;
    })

    // if(data.result.order_refund_code == null){
    //     order_refund_code = '<p style="color: red">N/A</p>'
    // }

    if(data.result.order_request_id == null){
        data.result.order_request_id = '<p style="color: red">N/A</p>';
    }

    if(data.result.order_id == null){
        data.result.order_id = '<p style="color: red">N/A</p>';
    }

    if(data.result.user_name == null){
        data.result.user_name = '<p style="color: red">N/A</p>';
    }

    if(order_payment == null){
        order_payment = '<p style="color: red">N/A</p>';
    }

    if(data.result.web_name == null){
        data.result.web_name = '<p style="color: red">N/A</p>';
    }

    if(data.result.order_request_id == null){
        data.result.order_request_id = '<p style="color: red">N/A</p>';
    }

    if(data.result.order_trans_id == null){
        data.result.order_trans_id = '<p style="color: red">N/A</p>';
    }

    if(data.result.order_paytype == null){
        data.result.order_paytype = '<p style="color: red">N/A</p>';
    }

    $('#order_id').html(data.result.order_id);
    $('#user_name').html(data.result.user_name);
    $('#order_payment_status').html(order_payment_status);
    $('#order_payment').html(order_payment);
    $('#web_name').html(data.result.web_name);
    $('#order_request_id').html(data.result.order_request_id);
    $('#order_trans_id').html(data.result.order_trans_id);
    $('#order_sum_price').html(data.result.order_sum_price);
    $('#order_paytype').html(data.result.order_paytype);
    $('#order_datetime').html(data.result.order_datetime);
    $('#order_status').html(order_status);
    $('#user_number_phone').html(data.result.user_number_phone);
    $('#user_email').html(data.result.user_email);
    $('#order_description').html(data.result.order_description);
    $('#order_detail').html(order_detail);
    $('#order_suspicious').html(order_suspicious);
    // $('#order_refund_code').html(order_refund_code);

    $.fn.digits = function () {
        return this.each(function () {
        $(this).text(
            $(this)
            .text()
            .replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
        );
        });
    };

    $(".amount-number").digits();
    $("#order_sum_price").digits();
}

function tooltip(element, maxLength){
    let description = $(element);
    $.each(description, function () { 
        if($(this).text().length > maxLength){
        var stringOriginal = $(this).text();
        var subString = $(this).text().substring(0, maxLength) + '...';
        $(this).text(subString);
        $(this).attr('title', stringOriginal);
        }
    });
}
  
function formatRepoWebsite (repo) {
    if (repo.loading) {
        return repo.text;
    }

    var $container = $(
        "<div class='select2-result-website clearfix' id='result_website_"+repo.id+"'>" +
        "<div class='select2-result-website__icon'><img src='" + base_url + repo.image + "' /></div>" +
        "<div class='select2-result-website__meta'>" +
            "<div class='select2-result-website__title'></div>" +
            "<div class='select2-result-website__description'></div>" +
        "</div>" +
        "</div>"
    );

    $container.find(".select2-result-website__title").text(repo.text);
    $container.find(".select2-result-website__description").text(repo.description)

    return $container;
}
  
function formatRepoSelectionWebsite (state) {
    if (!state.id) {
    return state.text;
    }
    var $state = $(
    '<span id = "website_'+ state.id +'"><img class="img-flag" /> <span></span></span>'
    );

    // Use .text() instead of HTML string concatenation to avoid script injection issues
    $state.find("span").text(state.text);
    $state.find("img").attr("src", base_url + state.image);

    return $state;
} //End Of Function Website Select2
  
function checkdefault(default_value, check_parameter){
    if(check_parameter == null || check_parameter==""){
    return default_value;
    }
    return check_parameter;
}
  
  // show alert
function showAlert(type, message){
    $('.alert').removeClass("alert-success");
    $('.alert').removeClass("alert-warning");
    $('.alert').removeClass("alert-danger");

    switch(String(type)){
        case "success":
        $('.alert').addClass('alert-success');
        $('.alert-heading').html('<i class="fas fa-check-circle"></i> Success!');
        break;
        case "error":
        $('.alert').addClass('alert-danger');
        $('.alert-heading').html('<i class="fas fa-exclamation-circle"></i> Error!');
        break;
        case "warning":
        $('.alert').addClass('alert-warning');
        $('.alert-heading').html('<i class="fa fa-warning"></i> Warning!');
        break;
    }

    $('.alert .message').html(message);
    $('.alert').addClass('d-block');
    setTimeout(function(){ $('.alert').removeClass('d-block'); }, 3000);

    $('.alert button.close').on('click', function(){
        $('.alert').removeClass('d-block');
    });
}