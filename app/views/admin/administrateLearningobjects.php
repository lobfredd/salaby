<script xmlns="http://www.w3.org/1999/html">
    function addLearningObject () {
        ajaxCall("GET", "/admin/addLearningObject", true, "addLearningObject");
    }

    function doAddLearningObject () {
        var submit = document.getElementById("submitBtn");
        submit.click();
    }

    function deleteLearningObject (object, learningObjectId) {
        if (confirm('Er du sikker på at du vil fjerne dette læringsobjektet?')) {
            ajaxCall("GET", "/admin/doDeleteLearningObject/" + learningObjectId, true);
            $(object).closest("tr").remove();
        }
    }
</script>
<div id="content" class="widthConstrained">
    <?php $this->showNotice();?>
    <section class="adminMenu">
        <a href="/admin/administrateSchools">Brukeradministrasjon</a>
        <a  href="/admin/administrateSubjects">Fag</a>
        <a href="/admin/administrateCategories">Kategorier</a>
        <a style="background-color: #FF8700;" href="/admin/administrateLearningobjects">Læringsobjekter</a>
    </section>
    <div class="tableBG">
        <section id="topMenu">
            <span class="submit" onclick="addLearningObject()">
                Legg til læringsobjekt
            </span>

                <form method="post" action="/admin/administrateLearningobjects" class="form-wrapper">
                    <input type="text" id="search" name="searchBoxLearningObjects" placeholder="Søk etter læringsobjekt...">
                    <input type="submit" value="søk" class="submit">
                </form>
        </section>

        <section id="maintable">
            <form id="addLearningObjectsForm" method="post" action="/admin/actuallyAddLearningObject" enctype="multipart/form-data">
        <table>
            <tr>
                <th>Tittel</th>
                <th>Ikon</th>
                <th>Læringsobjekt</th>
                <th></th>
            </tr>
            <tr id="addLearningObject"></tr>
            <?php foreach ($this->learningObjects as $learningObject) { ?>
                <tr>
                    <td><?php echo $learningObject['title']?></td>
                    <td><img src="<?php echo $learningObject['imgurl']?>" width="35"></td>
                    <td><a href="<?php echo $learningObject['link']?>">Gå til læringsobjektet</a></td>
                    <td class="editDeleteColumn"><a href="/admin/editLearningobjects/<?php echo $learningObject['id']; ?>"><div title="Rediger læringsobjekt" class="editBtn"></div></a>
                        <div title="Slett læringsobjekt" onclick="deleteLearningObject(this, <?php echo $learningObject['id'];?>)" class="deleteBtn"></td>
                </tr>
            <?php } ?>
        </table>
            </form>
            </section>
    </div>
</div>