<?
    require("inc_security.php");
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?= $load_header ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link href="../../../plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../resource/css/configuations.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../../plugins/select2/js/select2.min.js"></script>
    <script language="javascript" src="../../resource/js/configuationsModule.js"></script>
</head>

<body>
    <div class="main_container">
        <div class="pick_website_container">
            <div class="title_pick_website title">
                <h4><?=translate_text('Chọn Trang Web')?>: </h4>
            </div>
            <div class="box">
                <select class="pick_website_select" style="width: 20%;">
                </select>
            </div>
        </div>
        <div class="configuations_container">
            <div class="title_configuations title">
                <h4><?=translate_text('Chỉnh Sửa')?>: </h4>
            </div>

            <div class="configuations">
                <form>
                    <div class="form-group form-configuations">
                        <label for="input-admin-email"><?=translate_text('Admin Email')?></label>
                        <input type="email" class="form-control" id="input-admin-email" placeholder="<?=translate_text('Thêm Admin Email')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-site-title"><?=translate_text('Tiêu Đề Website')?></label>
                        <input type="text" class="form-control" id="input-site-title" placeholder="<?=translate_text('Thêm Tiêu Đề')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-meta-description"><?=translate_text('Meta Description')?></label>
                        <input type="text" class="form-control" id="input-meta-description" placeholder="<?=translate_text('Meta Description')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-meta-keyword"><?=translate_text('Meta Keyword')?></label>
                        <input type="text" class="form-control" id="input-meta-keyword" placeholder="<?=translate_text('Meta Keyword')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-rewrite"><?=translate_text('Rewrite')?></label>
                        <input type="text" class="form-control" id="input-rewrite" placeholder="<?=translate_text('Rewrite')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-extention"><?=translate_text('Đuôi Mở Rộng')?></label>
                        <input type="text" class="form-control" id="input-extention" placeholder="<?=translate_text('Thêm Đuôi Mở Rộng')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="pick_language"><?=translate_text('Ngôn Ngữ')?></label>
                        <select id="pick_language" class="pick_language form-control" style="width: 20%;"></select>
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-hotline"><?=translate_text('Hotline')?></label>
                        <input type="text" class="form-control" id="input-hotline" placeholder="<?=translate_text('Thêm Hotline')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-hotline-banhang"><?=translate_text('Hotline Bán Hàng')?></label>
                        <input type="text" class="form-control" id="input-hotline-banhang" placeholder="<?=translate_text('Thêm Hotline Bán Hàng')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-hotline-kythuat"><?=translate_text('Hotline Kỹ Thuật')?></label>
                        <input type="text" class="form-control" id="input-hotline-kythuat" placeholder="<?=translate_text('Thêm Hotline Kỹ Thuật')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-address"><?=translate_text('Địa Chỉ')?></label>
                        <input type="text" class="form-control" id="input-address" placeholder="<?=translate_text('Thêm Địa Chỉ')?>">
                    </div>
                    <div class="form-check form-configuations">
                        <label class="form-check-active-contact-label" for="check-active-contact"><?=translate_text('Kích Hoạt Liên Hệ')?></label>
                        <input type="checkbox" class="form-check-input-active-contact" id="check-active-contact">
                    </div>
                    <div class="form-group form-configuations">
                        <div>
                            <label class="form-background-homepage-label" for="input-background-homepage"><?=translate_text('Ảnh Nền Homepage')?></label>
                            <div class="form-image-input">
                                <div class="input-image-container">
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="input-image" id="input_image_1">
                                        <img id="image_background_homepage_1" src="#"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>                                        
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_1">
                                    </div>
                                    <p><?= translate_text('Hình 1')?></p>
                                </div>
                                <div class="input-image-container">
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="input-image" id="input_image_2"> 
                                        <img id="image_background_homepage_2" src="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>                                        
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_2">
                                    </div>
                                    <p><?= translate_text('Hình 2')?></p>
                                </div>
                                <div class="input-image-container">
                                    <i id="font-clear-image-3" class="fas fa-trash-alt"></i>
                                    <div class="input-image" id="input_image_3">
                                        <img id="image_background_homepage_3" src="#"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>                                        
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_3">
                                    </div>
                                    <p><?= translate_text('Hình 3')?></p>
                                </div>
                                <div class="input-image-container">
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="input-image"id="input_image_4">
                                        <img id="image_background_homepage_4" src="#"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>                                        
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_4">
                                    </div>
                                    <p><?= translate_text('Hình 4')?></p>
                                </div>
                                <div class="input-image-container">
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="input-image" id="input_image_5">
                                        <img id="image_background_homepage_5" src="#"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_5">
                                    </div>
                                    <p><?= translate_text('Hình 5')?></p>
                                </div>
                                <div class="input-image-container" >
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="input-image" id="input_image_6"> 
                                        <img id="image_background_homepage_6" src="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>                                        
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_6">
                                    </div>
                                    <p><?= translate_text('Hình 6')?></p>
                                </div>
                                <div class="input-image-container">
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="input-image" id="input_image_7"> 
                                        <img id="image_background_homepage_7" src="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-5v5h-2v-5h-5v-2h5v-5h2v5h5v2z"/></svg>                                        
                                        <input type="file" class="form-input-background-homepage" id="input-background-homepage_7">
                                    </div>
                                    <p><?= translate_text('Hình 7')?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-payment"><?=translate_text('Thông Tin Thanh Toán')?></label>
                        <input type="text" class="form-control" id="input-payment" placeholder="<?=translate_text('Thêm Thông Tin Thanh Toán')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-fee-transport"><?=translate_text('Thông Tin Vận Chuyển')?></label>
                        <input type="text" class="form-control" id="input-fee-transport" placeholder="<?=translate_text('Thêm Thông Tin Vận Chuyển')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-contact-sale"><?=translate_text('Liên Hệ Mua Hàng')?></label>
                        <input type="text" class="form-control" id="input-contact-sale" placeholder="<?=translate_text('Thêm Liên Hệ Mua Hàng')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-info-company"><?=translate_text('Thông Tin Công Ty')?></label>
                        <input type="text" class="form-control" id="input-info-company" placeholder="<?=translate_text('Thêm Thông Tin Công Ty')?>">
                    </div>
                    <div class="form-group form-configuations">
                        <label for="input-address"><?=translate_text('')?></label>
                        <input type="text" class="form-control" id="input-address" placeholder="<?=translate_text('Thêm Địa Chỉ')?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</body>