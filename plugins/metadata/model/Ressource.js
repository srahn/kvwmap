class Ressource {
  constructor(data) {
    this.data = data;
    this.layer_id = 8; // ToDo pk ersetzen durch eine Konstante
    // console.log(this.data);
  }

  get = (key) => {
    // console.log('Get Value for Key: ' + key);
    return this.data[key];
  }

  set = (key, value) => {
    this.data[key] = value;
    return this.value;
  }

  showButton = () => {
    const btn_id = 'button_' + (this.get('pack_status_id') ? this.get('pack_status_id') : '1') + '_' + this.get('id');
    document.querySelectorAll(`.odr-button[data-ressource_id='${this.get('id')}']`).forEach((btn) => {
      if (btn.id == btn_id) {
        // console.log(`show button ${btn.id}`);
        btn.style.display = 'inline-block';
      }
      else {
        // console.log(`hide button ${btn.id}`);
        btn.style.display = 'none';
      }
    });
  }

  updateGUI = () => {
    this.showButton();
    document.getElementById('status_span_' + this.get('id')).innerHTML = `${this.get('status')} (${this.get('status_id')})`;
    document.getElementById('ressource_id_span_' + this.get('id')).innerHTML = (this.get('id') ? ' (' + this.get('id') + ')' : '');
    document.getElementById('num_ressources_span').innerHTML = numRessources();
    // document.getElementById('num_uptodate_ressources_span').innerHTML = numUptodateRessources();
    // document.getElementById('num_outdated_ressources_span').innerHTML = numOutdatedRessources();
    // document.getElementById('num_ordered_ressources_span').innerHTML = numOrderedRessources();
    // document.getElementById('num_ressources_in_progress_span').innerHTML = numRessourcesInProgress();
  }
}