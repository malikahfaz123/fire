window.initMap = function(id,postal_code = null,city = null,state = null){
    const input = document.getElementById(id);
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.setFields(["address_components", "geometry", "icon", "name"]);
    autocomplete.addListener("place_changed", () => {
        const places = autocomplete.getPlace();
        if (!places.geometry) {
            window.alert("No details available for input: '" + places.name + "'");
            return;
        }
        handleAddressChange(places,postal_code,city,state);
    });
};

function handleAddressChange(places,postal_code,city,state) {
    places.address_components.map(function (value) {
        let type = value.types[0];
        switch(type){
            case 'administrative_area_level_1':
                if(state){
                    document.querySelector(state).value = value.long_name;
                }
                break;
            case 'locality':
                break;
            case 'neighborhood':
                break;
            case 'administrative_area_level_2':
                if(city){
                    long_name = value.long_name;
                    long_name = long_name.replace('City','');
                    document.querySelector(city).value = long_name;
                }
                break;
            case 'postal_code':
                if(postal_code){
                    document.querySelector(postal_code).value = value.short_name;
                }
                break;
        }
    });
}
