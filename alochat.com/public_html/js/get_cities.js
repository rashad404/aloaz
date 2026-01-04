function getCities(element,withPrompt) {

    var countryId = element.value;

    var citiesSelect = $(".dynamic-city-input");

    citiesSelect.prop("disabled",true);
    citiesSelect.empty();

    $.get("/profile/get-cities",
        {
            country_id: parseInt(countryId),
            with_prompt:withPrompt
        },
        function (data) {
            
            if(data) {

                for (var i = 0; i < data.length; ++i) {
                    citiesSelect.append($('<option>', {
                        value: data[i].id,
                        text : data[i].name
                    }));
                }

                citiesSelect.prop("disabled",false);
            }
        } /* Success function*/

    ); /* Ajax get */
}