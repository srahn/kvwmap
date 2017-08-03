<h2 style="
	margin-top: 10px;
	margin-bottom: 10px
">Deploy Fachschale Wasserrecht</h2><p>
<h3>Update MySQL-Datenbank</h3>
<?php echo $this->result['update_mysql']; ?>

<h3>Pull Git Repository</h3>
<?php echo $this->result['pull_git']; ?>

<h3>Migrate PostgreSQL</h3>
<?php echo $this->result['migrate_pgsql']; ?>

<h3>Reset PostgreSQL Data</h3>
<?php echo $this->result['reset_pgsql_data']; ?>