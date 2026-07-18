<?php

if (!empty($post)) {

?>


    <!--div class="fa-3x">
        <i class="fas fa-spinner fa-spin"></i>
        <i class="fas fa-circle-notch fa-spin"></i>
        <i class="fas fa-sync fa-spin"></i>
        <i class="fas fa-cog fa-spin"></i>
        <i class="fas fa-spinner fa-pulse"></i>
        <i class="fas fa-stroopwafel fa-spin"></i>
    </div-->

    <div class="central-meta new-pst">
        <div class="new-postbox">
            <figure>
                <a href="<?php echo Router::url(array('controller' => 'profil', 'action' => 'view', 'slug' => mb_strtolower(Inflector::slug($AuthUser['Account']['fullname'])), 'id' => $AuthUser['Info']['id'])) ?>">
                    <img src="<?php echo Router::url(Op::resizedURL($User['Account']['profil'], array('width' => 60, 'height' => 60, 'quality' => 80, 'space' => false))) ?>" alt="">
                </a>
            </figure>
            <div class="newpst-input">






            <?php
                echo $this->Html->css('/fileup/file-upload-with-preview.min.css', array('once' => true));
            ?>












                <?php echo $this->Form->create(false,  array('type' => 'file', 'novalidate' => true, 'url' => array('controller' => 'ajax', 'action' => 'postnew'), 'id' => 'ConnexionForm', 'class' => 'wmin-sm-400', 'inputDefaults' => array('label' => false, 'div' => false, 'class'=> false, 'error' => array('attributes' => array('class' => 'error-message w-100')))));

                ?>

                    <?php echo $this->Form->hidden('info_id', array('value' => $AuthUser['Info']['id'])); ?> 
                    <?php echo $this->Form->input('content', array('type' => 'textarea', 'rows' => '2', 'placeholder' => 'Quoi de neuf')); ?> 




            <div style="display: inline-block; width: 100%;">



                <div class=custom-file-container data-upload-id=myFirstImage>


                    <div class="attachments">
                        <ul>
                            <li>
                                <i class="fa fa-image"></i>
                                <label class="fileContainer">
                                <?php
                                echo $this->Form->input('File..media_file', array('id' => 'fileUpload1','type' => 'file','multiple' => true,'div' => false, 'label' => false));
                                ?>
                                </label>
                            </li>

                            <li>
                                <?php echo $this->Form->button('Poster', array('type' => 'submit', 'name' => 'form', 'value' => 'newpost')); ?>
                            </li>
                        </ul>
                    </div>




                    <label style="display: none">
                        <a href=javascript:void(0) class=custom-file-container__image-clear title="Clear Image">&times;</a>
                    </label> 
                    <label class=custom-file-container__custom-file style="display: none">
                        <!--input type=file name=moi_file class=custom-file-container__custom-file__custom-file-input accept=* multiple=multiple aria-label="Choose File"--> 

                        <!--input type=hidden name=MAX_FILE_SIZE value=104857600--> 
                        <span class=custom-file-container__custom-file__custom-file-control></span>
                    </label>
                    <div class=custom-file-container__image-preview></div>
                </div>
            </div>

            <!--div class=demo-info-container role=contentinfo><div><a href=javascript:void(0) class="upload-info-button upload-info-button--first">Get first file info</a> <a href=javascript:void(0) class="upload-info-button upload-info-button--second">Get second file-group info</a> <span>Output will be in the console.</span></div></div-->

            <!--script src=https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.js></script>
            <script src=https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.js></script-->
            <script type="text/javascript" src="<?php echo Router::url('/fileup/file-upload-with-preview.min.js') ?>"></script>


<script>

var upload = new FileUploadWithPreview('myFirstImage', {
    maxFileCount: 4,
    showDeleteButtonOnImages: true,
    /*
    text: {
        chooseFile: 'Custom Placeholder Copy',
        browse: 'Custom Button Copy',
        selectedCount: 'Custom Files Selected Copy',
    },

    presetFiles: [
        './badge.png',
        'https://images.unsplash.com/photo-1557090495-fc9312e77b28?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80',
    ],
    */
});

</script>



                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div><!-- add post new box -->

    <style type="text/css">
        .custom-file-container__image-preview{
            display: none;
        }
    </style>

    <script type="text/javascript">

        $("#fileUpload1").on('change',function() {
            $(".custom-file-container__image-preview").css('display','inline-block');
            //$(".custom-file-container__image-preview").attr('style','display:inline-block');
        });

    </script>



<?php

}

?>


    <script src="https://www.gstatic.com/firebasejs/8.8.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.8.0/firebase-database.js"></script>
    <script type="text/javascript">
        var allUser = new Array();

        function cominsert(messages, CommID){
            udata = allUser[messages.usr];
            msgfire = '<li><div class="comet-avatar ' + udata.roleclass + '"><a href="' + udata.slug + '"><img src="' + udata.profil + '" class="' + udata.roleclass + '" alt=""></a></div><div class="we-comment"><div class="coment-head"><h5><a href="' + udata.slug + '" title="">' + udata.fullname + '</a></h5><span>comment</span></div><p>' + messages.msg + '</p></div></li>';

            var parent = jQuery("#"+CommID+" .we-comet .showmore").parent("li");
            $(msgfire).insertBefore(parent);

        }


        const firebaseConfig = {
            apiKey: "AIzaSyBLnG1plR0b3p7sgZrE6LK5AJ9iUiMcboI",
            authDomain: "cervo-40417.firebaseapp.com",
            databaseURL: "https://cervo-40417-default-rtdb.firebaseio.com",
            projectId: "cervo-40417",
            storageBucket: "cervo-40417.appspot.com",
            messagingSenderId: "397339374180",
            appId: "1:397339374180:web:c92c421d07e596fd4c6571",
            measurementId: "G-0DRFBJ8W35"
        };
        firebase.initializeApp(firebaseConfig);

    </script>


<?php


foreach ($NewsFeeds as $key => $NewsFeed) {
    echo $this->element('Post/fil', array('NewsFeed' => $NewsFeed));

    if ($key == 1 AND !empty($post)) {
        echo '<div class="row"><div class="col-md-12 d-lg-none"><aside class="sidebar static">'.$this->element('widget-page', array('Schools' => $SuggestionSchools)).'</aside></div></div>';
    }

}

?>
