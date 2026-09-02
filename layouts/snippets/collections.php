<?
  include(LAYOUTPATH.'languages/collection_formular_'.rolle::$language.'.php');
?>
<h2 style="margin: 20px"><?php echo htmlspecialchars($strListTitel); ?></h2>

<?php if (!empty($this->collections)) { ?>

    <?php
    // Attribute/Spalten aus dem ersten Objekt ermitteln
    $attributes = array_keys($this->collections[0]->data);
    ?>

    <table id="collection-table" class="sortable-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bezeichnung</th>
                <th>Gruppe&nbsp;ID</th>
                <th>Filter</th>
                <th>Extent</th>
                <th>Aktionen</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this->collections as $collection) { ?>
                <tr class="listen-tr no-sync no-shared">
                    <?php foreach ($attributes as $attribute) { ?>
                        <td>
                            <?php
                            echo htmlspecialchars(
                                $collection->data[$attribute] ?? ''
                            );
                            ?>
                        </td>
                    <?php } ?>

                    <td class="actions">
                        <a href="?go=collection_editor&selected_collection_id=<?php echo urlencode($collection->get_id()); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="Bearbeiten">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="?go=collection_delete&selected_collection_id=<?php echo urlencode($collection->get_id()); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="Löschen" onclick="return confirm('Soll dieser Datensatz wirklich gelöscht werden?');">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php } else { ?>

    <p>Keine Datensätze vorhanden.</p>

<?php } ?>


<style>
.sortable-table {
    border-collapse: collapse;
    width: 100%;
}

.sortable-table th,
.sortable-table td {
    padding: 6px 10px;
    border: none;
}

.sortable-table th {
  cursor: pointer;
  color: firebrick;
  TEXT-DECORATION: none;
  font-size: 17px;
  border: none;
  outline: none;
}

.sortable-table th:hover {
  color: #52100f;
}

.sortable-table .actions {
    text-align: center;
    white-space: nowrap;
    width: 1%;
}

.sortable-table .actions a {
    text-decoration: none;
    margin: 0 4px;
}
</style>


<script>
document.querySelectorAll('#collection-table th').forEach((header, columnIndex) => {

    // Aktionsspalte nicht sortierbar
    if (columnIndex === document.querySelectorAll('#collection-table th').length - 1) {
        return;
    }

    header.addEventListener('click', function () {

        const table = document.getElementById('collection-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        const ascending = this.dataset.sortDirection !== 'asc';

        rows.sort((a, b) => {

            const valueA = a.cells[columnIndex].textContent.trim();
            const valueB = b.cells[columnIndex].textContent.trim();

            // Numerische Werte numerisch sortieren
            if (valueA !== '' && valueB !== '' &&
                !isNaN(valueA) && !isNaN(valueB)) {

                return ascending
                    ? Number(valueA) - Number(valueB)
                    : Number(valueB) - Number(valueA);
            }

            // Text alphabetisch sortieren
            return ascending
                ? valueA.localeCompare(valueB, 'de')
                : valueB.localeCompare(valueA, 'de');
        });

        rows.forEach(row => tbody.appendChild(row));

        // Sortierrichtung zurücksetzen
        table.querySelectorAll('th').forEach(th => {
            delete th.dataset.sortDirection;
        });

        this.dataset.sortDirection = ascending ? 'asc' : 'desc';
    });
});
</script>