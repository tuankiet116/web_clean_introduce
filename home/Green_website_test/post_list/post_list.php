<?php
require_once("../helper/function.php");
require_once('../helper/url.php');

$get_web_id = get_data_row("SELECT * FROM website_config WHERE web_url = '$main_url' AND web_active = 1");
$web_id = $get_web_id['web_id'];
$web_icon = $get_web_id['web_icon'];

$get_web_icon = get_data_row("SELECT * FROM configuration WHERE web_id = $web_id");
$web_bottom_icon = $get_web_icon['con_logo_bottom'];

$get_url = get_data_row("SELECT con_rewrite_name_homepage FROM configuration WHERE web_id = $web_id");
foreach ($get_url as $key => $g_url) {
    $url = $g_url;
}

if (isset($_GET['url'])) {
    $url_rough = $_GET['url'];
    $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url_rough);
}

if (strpos($url, "/") != false) {
    $url = $g_url;
    header('location: ../');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$get_cmp_rewrite = get_data_row("SELECT * FROM categories_multi_parent WHERE FIND_IN_SET('$id', post_type_id)");

$get_pt_title = get_data_row("SELECT * FROM post_type WHERE post_type_id = $id");

$category = get_data_rows("SELECT * FROM categories_multi_parent WHERE web_id = $web_id");

$post_type = get_data_rows("SELECT * FROM post_type WHERE web_id = $web_id");

$post_content = get_data_rows("SELECT * FROM post_detail");

/********** LEFT TITLE  **********/

$arr_left_title = get_data_rows("SELECT * FROM post_type");

/********** CONTACT **********/

$arr_contact = get_data_rows("SELECT * FROM configuration WHERE web_id = $web_id");

?>

<!DOCTYPE html>
<html>

<head>
    <title> <?php echo $get_pt_title['post_type_title'] ?> </title>
    <? include("./p_list_head.php"); ?>
    <link rel="stylesheet" href="../Green_website/resource/css/news.css">
</head>

<body>
    <!--------------- HEADER --------------->

    <? include('../includes/inc_header.php') ?>

    <!--------------- CONTENT --------------->

    <div class="container">
        <div class="row">
            <div class="col-lg-3 order-lg-1 order-md-2 order-sm-2 order-2">
                <div class="news-left">
                    <div class="news-left-title">
                        <a href="" target="_self">
                            Chuyên mục khác
                        </a>
                    </div>

                    <div class="news-left-content">
                        <ul class="list-post">
                            <?php
                            $other_cate = get_data_rows("SELECT * FROM categories_multi_parent WHERE cmp_parent_id IS NOT NULL AND cmp_active = 1 AND web_id = $web_id");
                            foreach ($other_cate as $key => $oc) {
                                $oc_id = $oc['cmp_id'];
                                $oc_p = get_data_rows("SELECT * FROM post WHERE cmp_id = $oc_id LIMIT 1");

                                $oc_cmp_id = $oc['cmp_id'];
                                $oc_pt_id = $oc['post_type_id'];
                                $oc_pt = explode(",", $oc_pt_id);
                                $count_oc_pt = count($oc_pt);
                                if ($count_oc_pt == 1) {
                                    foreach ($oc_p as $key => $op) {

                                        $changeUrlId = 'id=' . $oc['post_type_id'];

                                        echo '
                                            <li>
                                                <a href="../post_list/post_list.php?' . $changeUrlId . '" target="_self">
                                                    <i class="fas fa-chevron-right"></i>
                                                    ' . $oc['cmp_name'] . '
                                                </a>
                                            </li>
                                ';
                                    }
                                } else if ($count_oc_pt > 1) {
                                    if ($oc_pt_id != "" || $oc_pt_id != null) {
                                        $topic_post = get_data_rows("SELECT * FROM post WHERE cmp_id = $oc_cmp_id AND post_active = 1 AND post_type_id IN ($oc_pt_id)");
                                        $mod_rewrite = $arr_con['con_mod_rewrite'];
                                        if ($mod_rewrite == 1) {
                                            if ($oc['cmp_rewrite_name'] != "" || $oc['cmp_rewrite_name'] != null) {
                                                $changeUrlName = 'name=' . $oc['cmp_rewrite_name'];
                                            } else if ($oc['cmp_rewrite_name'] == "" || $oc['cmp_rewrite_name'] == null) {
                                                $changeUrlName = 'cid=' . $oc['cmp_id'];
                                            }
                                        } else {
                                            $changeUrlName = 'cid=' . $oc['cmp_id'];
                                        }

                                        echo '
                                            <li>
                                                <a href="../post_type_list/post_type_list.php?' . $changeUrlName . '" target="_self">
                                                    <i class="fas fa-chevron-right"></i>
                                                    ' . $oc['cmp_name'] . '
                                                </a>
                                            </li>
                                        ';
                                    } else if ($oc_pt_id == "" || $oc_pt_id == null) {
                                        echo '
                                            <li>
                                                <a href="error.php" target="_self">
                                                    <i class="fas fa-chevron-right"></i>
                                                    ' . $oc['cmp_name'] . '
                                                </a>
                                            </li>
                                        ';
                                    }
                                } else if ($count_oc_pt == 0) {
                                    echo '
                                        <li>
                                            <a href="error.php" target="_self">
                                                <i class="fas fa-chevron-right"></i>
                                                ' . $oc['cmp_name'] . '
                                            </a>
                                        </li>
                                    ';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <?php
                foreach ($arr_contact as $key => $contact) {
                    if ($contact['con_banner_active'] == 1) {
                        echo '
                                <div class="news-left">
                                    <div class="news-left-title">
                                        <a href="#" target="_self">
                                            Hỗ trợ trực tuyến
                                        </a>
                                    </div>

                                    <div class="news-left-content">
                                        <div class="hotline-title">Trực tuyến</div>

                                        <div class="hotline-logo">
                                            <img src="' . $base_url . 'data/image/post/hotline3.png" alt="hotline">
                                        </div>
              
                                        <div class="hotline-contact">
                                            <a href="tel:' . $contact['con_hotline'] . '" target="_self">
                                                <i class="fas fa-phone-alt"></i>
                                                ' . $contact['con_hotline'] . '
                                            </a>
                                        </div>
                                
                                        <div class="hotline-contact">
                                            <a href="#" target="_self">
                                                <i class="far fa-envelope"></i>
                                                ' . $contact['con_admin_email'] . '
                                            </a>
                                        </div>
                                        
                                        <div class="hotline-social-media">
                                            <table>
                                                <tr>      
                                                    <td>
                                                        <a href="' . $contact['con_link_fb'] . '" target="_self">
                                                            <i class="fab fa-facebook-square fb"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="' . $contact['con_link_twitter'] . '" target="_self">
                                                            <i class="fab fa-twitter-square tw"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="' . $contact['con_link_insta'] . '" target="_self">
                                                            <i class="fab fa-instagram-square ins"></i>
                                                        </a>
                                                    </td>                                    
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="hotline-support">
                                            <div class="hotline-title"> Hỗ trợ </div>

                                            <div class="hotline-contact">
                                                <a href="tel:' . $contact['con_hotline_hotro_kythuat'] . '" target="_self" style="font-size: 22px">
                                                    <i class="fas fa-mobile-alt"></i>
                                                    ' . $contact['con_hotline_hotro_kythuat'] . '
                                                </a>
                                            </div>

                                            <div class="hotline-contact">
                                                <a href="#" target="_self">
                                                    <i class="far fa-envelope"></i>
                                                    ' . $contact['con_admin_email'] . '
                                                </a>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                            ';
                    }
                }
                ?>
            </div>

            <?php $bread_topic = get_data_rows("SELECT * FROM categories_multi_parent WHERE web_id = $web_id ") ?>
            <div class="news-right col-lg-9 order-lg-2 order-md-1 order-sm-1 order-1">
                <div class="news-right-container">
                    <div class="breadcrumb">
                        <a href="../index.php" target="_self"> Trang chủ </a>

                        <span class="navigation-pipe">
                            <i class="fas fa-chevron-right"></i>
                        </span>

                        <?php
                        if ($get_cmp_rewrite['cmp_parent_id'] != "" || $get_cmp_rewrite['cmp_parent_id'] != null) {
                            $get_cmp_pr_id = $get_cmp_rewrite['cmp_parent_id'];
                            $get_parent_name = get_data_row("SELECT * FROM categories_multi_parent WHERE cmp_id = $get_cmp_pr_id");
                            echo '
                                    <a href="#" target="_self">' . $get_parent_name['cmp_name'] . '</a>

                                    <span class="navigation-pipe">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>

                                    <a href="#" target="_self">' . $get_cmp_rewrite['cmp_name'] . '</a>
                                ';
                        } else if ($get_cmp_rewrite['cmp_parent_id'] == "" || $get_cmp_rewrite['cmp_parent_id'] == null) {
                            echo '
                                    <a href="#" target="_self">' . $get_cmp_rewrite['cmp_name'] . '</a>
                                ';
                        }
                        ?>
                    </div>

                    <div class="news-right-content">

                        <p class="news-right-content-title"> <?php echo $get_pt_title['post_type_title'] ?> </p>

                        <?php
                        foreach ($post_type as $key => $p_type) {
                            if ($p_type['post_type_show'] != '' && $p_type['post_type_id'] == $id) {

                                $p_type_id = $p_type['post_type_id'];
                                $get_post = get_data_rows("SELECT * FROM post WHERE post_type_id = $p_type_id AND post_active = 1 ORDER BY post_datetime_create DESC");
                                foreach ($get_post as $key => $g_post) {
                                    foreach ($category as $key => $cate) {
                                        $mod_rewrite = $arr_con['con_mod_rewrite'];
                                        if ($mod_rewrite == 1) {
                                            if ($g_post['post_rewrite_name'] != "" || $g_post['post_rewrite_name'] != null) {
                                                $changeUrlName = 'name=' . $g_post['post_rewrite_name'];
                                            } else if ($g_post['post_rewrite_name'] == "" || $g_post['post_rewrite_name'] == null) {
                                                $changeUrlName = 'pid=' . $g_post['post_id'];
                                            }
                                        } else {
                                            $changeUrlName = 'pid=' . $g_post['post_id'];
                                        }

                                        $get_pt_name = get_data_rows("SELECT * FROM post_type WHERE post_type_active = 1 AND web_id = $web_id AND post_type_id = $p_type_id");
                                        foreach ($get_pt_name as $key => $pt_name) {
                                            if ($g_post['cmp_id'] == $cate['cmp_id'] && $g_post['post_image_background'] != '') {

                                                $date = $g_post['post_datetime_create'];
                                                $myDate = date("d-m-Y", strtotime($date));
                                                echo '
                                                        <div class="container post-list">
                                                            <div class="row">
                                                                <div class="post-list-img col-lg-4 col-md-4 col-sm-6 col-6">
                                                                    <a href="../news.php?' . $changeUrlName . '" target="_self">
                                                                        <img src="' . $base_url . $g_post['post_image_background'] . '" alt="post list image">
                                                                    </a>   
                                                                </div>

                                                                <div class="post-list-container col-lg-8 col-md-8 col-sm-6 col-6">                                                                                
                                                                    <div class="post-list-title">
                                                                        <a href="../news.php?' . $changeUrlName . '" target="_self">
                                                                            <p>' . $g_post['post_title'] . '</p>
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="post-list-date">' . $myDate . '</div>

                                                                    <div class="post-list-content">
                                                                        <p>' . $g_post['post_description'] . '</p>
                                                                    </div>
                                                                    
                                                                    <a href="../news.php?' . $changeUrlName . '" class="post-list-button" target="_self">Xem tiếp</a>
                                                                </div>
                                                            </div>                 
                                                        </div>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="contact-footer">
                        <h5>Mời liên hệ:</h5>
                        <?php
                        foreach ($arr_contact as $key => $call) {
                            if ($call['con_active_contact'] == 1) {
                                echo '
                                    <p> Địa chỉ: ' . $call['con_address'] . '</p>
                                    <p> Hotline: ' . $call['con_hotline'] . '</p>
                                ';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------- FOOTER --------------->

    <? include('../includes/inc_footer.php') ?>

    <? include('../includes/inc_foot.php') ?>
</body>

</html>