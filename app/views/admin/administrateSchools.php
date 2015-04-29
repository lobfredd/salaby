<script>
    function addSchool () {
        ajaxCall("GET", "/admin/doAddSchool", true, "addSchool");
    }

    function deleteSchool (object, schoolId) {
        ajaxCall("GET", "/admin/doDeleteSchool/" + schoolId, true);
        $(object).closest("tr").remove();
    }
</script>
<div id="content" class="widthConstrained">
    <?php include "PartialViews/adminMenu.php"?>
    <div id="schoolMainTable">
        <section id="topMenu">

            <div id="schoolAddButtonDiv" onclick="addSchool()">
                <button type="button" id="schoolAddButton"></button>
                <label id="addSchoolTxt">Legg til skole</label>
            </div>

            <div id="schoolSearch">
                <form method="post" action="/admin/administrateSchools" class="form-wrapper">
                    <input type="text" id="search" name="searchBoxSchools" placeholder="Søk etter skole...">
                    <input type="submit" value="søk" id="submit">
                </form>
            </div>

        </section>
        <section id="maintable">
            <table>
                <tr>
                    <th>Skolenavn</th>
                    <th>Fylke</th>
                    <th>Kommune</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr id="addSchool"></tr>
                <?php foreach ($this->schools as $school) { ?>
                    <tr>
                        <td><?php echo $school['name'] ?></td>
                        <td><?php echo $school['fylke'] ?></td>
                        <td><?php echo $school['kommune'] ?></td>
                        <td><div class="editBtn"></td>
                        <td><div onclick="deleteSchool(this, <?php echo $school['id'];?>)" class="deleteBtn"></td>
                    </tr>
                <?php }  ?>
            </table>
        </section>
    </div>
</div>