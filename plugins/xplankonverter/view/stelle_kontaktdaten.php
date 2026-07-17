<style>
  h2 {
    margin: 20px;
  }
  .form-group {
    display: flex;
    margin-bottom: 10px;
    align-items: center;
  }
  label {
    width: 150px;
    font-weight: bold;
    margin-right: 10px;
    text-align: right;
  }
  input {
    flex: 1;
    margin-right: 40px;
  }
</style>
<h2>Kontaktdaten zur Plan veröffentlichenden Stelle</h2>
  <div class="form-group">
    <label for="organisation">Organisation:</label>
    <input type="text" id="ows_distributionorganization" name="ows_distributionorganization" value="<? echo $this->Stelle->get('ows_distributionorganization'); ?>">
  </div>

  <div class="form-group">
    <label for="strasse">Straße / Hausnr.:</label>
    <input type="text" id="ows_distributionaddress" name="ows_distributionaddress" value="<? echo $this->Stelle->get('ows_distributionaddress'); ?>">
  </div>

  <div class="form-group">
    <label for="plz">Postleitzahl:</label>
    <input type="text" id="ows_distributionpostalcode" name="ows_distributionpostalcode" value="<? echo $this->Stelle->get('ows_distributionpostalcode'); ?>">
  </div>

  <div class="form-group">
    <label for="ort">Ort:</label>
    <input type="text" id="ows_distributioncity" name="ows_distributioncity" value="<? echo $this->Stelle->get('ows_distributioncity'); ?>">
  </div>
  <input type="hidden" name="stelle_id" value="<? echo $this->Stelle->id; ?>">  
  <input type="hidden" name="go" value="xplankonverter_stelle_kontaktdaten">  
  <input type="hidden" name="action" value="Speichern">
  <button type="submit">Absenden</button>
