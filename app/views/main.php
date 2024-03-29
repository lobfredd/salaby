<div id="content" class="widthConstrained">
    <div id="subjects" class="<?php echo $this->subjectsHTMLClass; ?>">
        <?php foreach($this->subjects as $subject) { ?>
            <a href="/forside/fag/<?php echo $this->classLevel; ?>-klasse/<?php echo slugify($subject['subjectname']); ?>"><div class="<?php echo $subject['htmlClasses'];?>"><img class="subjectimg" src="<?php echo $subject['imgurl']; ?>"><h4 class="subjectname"><?php echo $subject['subjectname']; ?></h4></div></a>
        <?php } ?>

    </div>

    <div class="subjectContent">
        <?php
        if(!arrayEmpty($this->categoryContent)){ ?>
            <div id="crumbs">
                <ul class="breadcrumb">
                    <li style="padding 0;"><a href="/"><img src="/public/img/house.png" height="20"></a>
                    </li>
               <?php foreach ($this->filePathURLS as $url){ ?>
                    <li><a class="filePathLinks" href="<?php echo $url[0]; ?>"><?php echo $url[1]; ?></a></li>
            <?php }  ?>
                </ul>
                <a class="mobileBackBtn" href="<?php $url = $this->filePathURLS;
                if(count($url) > 1) { echo $url[count($url) - 2][0] ."\"><-". $url[count($url) - 2][1];} else echo '/"><img src="/public/img/house.png" height="20">' ?></a>
            </div>
                <?php } ?>
        <?php foreach($this->categoryContent as $content) { ?>
            <a href="<?php echo $this->urlStr; if(isset($content['category'])) echo slugify($content['category']); else echo slugify($content['title']); ?>">
                <div class="<?php if (isset($content['category'])) echo 'category'; else echo 'lObject' ?>"
                     style="background-image: url(<?php echo $content['imgurl']; ?>)">
                    <h4 class="categoryname"><?php if(isset($content['category'])) echo $content['category']; else echo $content['title'];?></h4>
                </div>
            </a>
        <?php } ?>
    </div>

    <div id="game">
        <?php if(!($this->gameHTML == null)){ ?>
        <div id="crumbs">
            <ul class="breadcrumb">
                <li><a href="/"><img src="/public/img/house.png" height="15"></a></li>
               <?php foreach ($this->filePathURLS as $url){ ?>
                <li><a class="filePathLinks" href="<?php echo $url[0]; ?>"><?php echo $url[1]; ?></a></li>
            <?php }  ?>
            </ul>
            <a class="mobileBackBtn" href=" <?php $url = $this->filePathURLS;
            echo $url[count($url) - 2][0]; ?>"><-<?php echo $url[count($url) - 2][1]; ?></a>
        </div>
            <?php echo $this->gameHTML;
        }  ?>
    </div>



</div>

<?php if(!($this->subjectsHTMLClass === 'subjectsToggle')){
    echo '<img id="fly" src="/public/img/fly.png">';
}

