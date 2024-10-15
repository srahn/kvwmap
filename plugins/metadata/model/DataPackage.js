class DataPackage {
  constructor(data) {
    this.data = data;
    // console.log(this.data);
  }

  get = (key) => {
    // console.log('Get Value for Key: ' + key);
    return this.data[key];
  }

  getPackStatus = () => {
    return (this.get('pack_status') ?? '');
  }

  set = (key, value) => {
    this.data[key] = value;
    return this.value;
  }

  showButton = () => {
    const btn_id = 'button_' + (this.get('pack_status_id') ? this.get('pack_status_id') : '1') + '_' + this.get('ressource_id');
    document.querySelectorAll(`.dpt-button[data-ressource_id='${this.get('ressource_id')}']`).forEach((btn) => {
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
    document.getElementById('status_span_' + this.get('ressource_id')).innerHTML = this.getPackStatus();
    document.getElementById('package_id_span_' + this.get('ressource_id')).innerHTML = (this.get('id') ? ' (ID: ' + this.get('id') + ')' : '');
  }
}