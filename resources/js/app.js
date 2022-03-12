require('./bootstrap');

$(document).on('click', '.phone-button', function () {
    let button = $(this);
    axios.post(button.data('source')).then(function (response) {
        button.find('.number').html(response.data)
    }).catch(function (error) {
        console.error(error);
    });
});

//Regions select
/*$('.region-selector').each(function () {
    let block = $(this);
    let selected = block.data('selected');
    let url = block.data('source');

    let buildSelect = function (parent, items) {
        let current = items[0];

        let select = $('<select class="form-control">');
        let group = $('<div class="form-group">');

        select.append($('<option value=""></option>'));
        group.append(select);
        block.append(group);

        axios.get(url, {params: {parent: parent}})
            .then(function (response) {
                response.data.forEach(function (region) {
                    select.append(
                        $("<option>")
                            .attr('name', 'regions[]')
                            .attr('value', region.id)
                            .attr('selected', region.id === current)
                            .text(region.name)
                    );
                });
                if (current) {
                    buildSelect(current, items.slice(1))
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    };

    buildSelect(null, selected);
});*/
