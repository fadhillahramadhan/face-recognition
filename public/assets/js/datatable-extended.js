$.fn.dataTableLib = function (property) {
	let h = window.location.href.split('/').pop();
	let tableID = this;
	let dtTablePage = h.split('#')[1] == undefined ? 1 : h.split('#')[1];
	let searchItems = [];
	let dtTableFilter = '';
	let displaySearchItems = [];
	let isSearch = 0;
	let dtURLAction = [];
	let countTD = 0;
	let countSelectedTD = 0;
	let exportColumns = [];
	property.pagination = property.hasOwnProperty('pagination')
		? property.pagination
		: true;
	let data = $.setTable(tableID, property, dtTablePage);
	countTD = $.countTD(tableID, property);
	exportColumns['display'] = [];
	exportColumns['columns'] = [];
	exportColumns['align'] = [];
	$.each(property.colModel, function (key, value) {
		if (value.export == true) {
			exportColumns['display'].push(value.display);
			exportColumns['align'].push(value.align);
			if (value.alias != undefined) {
				exportColumns['columns'].push(value.alias);
			} else {
				exportColumns['columns'].push(value.name);
			}
		}
	});

	if (property.search === true) {
		$.each(property.searchItems, function (key, propName) {
			if (propName.type == 'text') {
				searchItems[propName.name] = 'string';
				displaySearchItems[propName.name] = propName.display;
			}
			if (propName.type == 'number') {
				searchItems[propName.name] = 'numeric';
				displaySearchItems[propName.name] = propName.display;
			}
			if (propName.type == 'select') {
				searchItems[propName.name] = 'select';
				displaySearchItems[propName.name] = propName.display;
				$.each(propName.option, function (key2, optionValue) {
					displaySearchItems[propName.name + optionValue.value] =
						optionValue.title;
				});
			}
			if (propName.type == 'date') {
				searchItems[propName.name + '[start]'] = 'date';
				searchItems[propName.name + '[end]'] = 'date';
				displaySearchItems[propName.name + '[end]'] = propName.display;
				displaySearchItems[propName.name + '[start]'] =
					propName.display;
			}
		});
	}
	$.each(property.buttonAction, function (key, url) {
		dtURLAction[url.action] = url.url;
	});

	$(document).on(
		'click',
		'.' + $(tableID).attr('id') + '-pagination a.page-link',
		function () {
			dtTablePage = $(this).attr('href');
			dtTablePage = dtTablePage.replace('#', '');
			if (dtTablePage !== '' && dtTablePage !== undefined) {
				let dtTableLimit = $(
					'.' + $(tableID).attr('id') + '-dtTable-limit'
				).val();
				$.updateTable(
					tableID,
					property,
					dtTableLimit,
					dtTablePage,
					dtTableFilter
				);
			}
		}
	);
	$(document).on(
		'change',
		'.' + $(tableID).attr('id') + '-dtTable-limit',
		function () {
			let dtTableLimit = $(this).val();
			dtTablePage = 1;
			$.updateTable(
				tableID,
				property,
				dtTableLimit,
				dtTablePage,
				dtTableFilter
			);
		}
	);
	$(document).on(
		'submit',
		'#' + $(tableID).attr('id') + '-dtTable-form',
		function (e) {
			$('#' + $(tableID).attr('id') + '-searchModal').modal('hide');
			dtTablePage = 1;
			e.preventDefault();
			let form = $(this).serializeArray();
			let indexOfArray = 0;
			dtTableFilter = '';
			let dateValue = [];
			let dateIteration = 0;
			$.each(form, function (i, field) {
				let filterType = '';
				if (searchItems[field.name] !== undefined) {
					filterType = searchItems[field.name];
					if (searchItems[field.name] == 'select')
						filterType = 'string';
					if (field.value != '') {
						if (searchItems[field.name] == 'date') {
							dateValue[dateIteration] = field.value;
							dateIteration++;
							if (dateIteration > 1) {
								let filterValue = dateValue.join('::');
								let filterName = field.name;
								filterName = filterName.replace('[end]', '');
								dtTableFilter =
									dtTableFilter +
									'&filter[' +
									indexOfArray +
									'][type]=' +
									filterType +
									'&filter[' +
									indexOfArray +
									'][field]=' +
									filterName +
									'&filter[' +
									indexOfArray +
									'][value]=' +
									filterValue;
								indexOfArray++;
								dateValue = [];
								dateIteration = 0;
								isSearch++;
							}
						} else {
							dtTableFilter =
								dtTableFilter +
								'&filter[' +
								indexOfArray +
								'][type]=' +
								filterType +
								'&filter[' +
								indexOfArray +
								'][field]=' +
								field.name +
								'&filter[' +
								indexOfArray +
								'][value]=' +
								field.value;
							indexOfArray++;
							isSearch++;
						}
					}
				}
			});
			let dtTableLimit = $(
				'.' + $(tableID).attr('id') + '-dtTable-limit'
			).val();
			$.setFilterNav(
				tableID,
				property,
				form,
				displaySearchItems,
				searchItems
			);
			$.updateTable(
				tableID,
				property,
				dtTableLimit,
				dtTablePage,
				dtTableFilter,
				isSearch
			);
		}
	);

	$(document).on(
		'click',
		'.' + $(tableID).attr('id') + '-filter-button',
		function () {
			dtTablePage = 1;
			if (typeof $(this).attr('date-start') !== 'undefined') {
				let val = $(this).val();
				$(
					'#' + $(tableID).attr('id') + '-form-data-' + val + 'start'
				).val('');
				$(
					'#' + $(tableID).attr('id') + '-form-data-' + val + 'end'
				).val('');
				isSearch--;
			} else {
				let val = $(this).val();
				$('#' + $(tableID).attr('id') + '-form-data-' + val).val('');
				isSearch--;
			}
			let dtTableLimit = $(
				'.' + $(tableID).attr('id') + '-dtTable-limit'
			).val();
			let form = $(
				'#' + $(tableID).attr('id') + '-dtTable-form'
			).serializeArray();
			let indexOfArray = 0;
			dtTableFilter = '';
			let dateIteration = 0;
			let dateValue = [];
			$.each(form, function (i, field) {
				let filterType = '';
				if (searchItems[field.name] !== undefined) {
					filterType = searchItems[field.name];
					if (searchItems[field.name] == 'select')
						filterType = 'string';
					if (field.value != '') {
						if (searchItems[field.name] == 'date') {
							dateValue[dateIteration] = field.value;
							dateIteration++;
							if (dateIteration > 1) {
								let filterValue = dateValue.join('::');
								let filterName = field.name;
								filterName = filterName.replace('[end]', '');
								dtTableFilter =
									dtTableFilter +
									'&filter[' +
									indexOfArray +
									'][type]=' +
									filterType +
									'&filter[' +
									indexOfArray +
									'][field]=' +
									filterName +
									'&filter[' +
									indexOfArray +
									'][value]=' +
									filterValue +
									'&filter[' +
									indexOfArray +
									'][comparison]=<';
								indexOfArray++;
								dateValue = [];
								dateIteration = 0;
							}
						} else {
							dtTableFilter =
								dtTableFilter +
								'&filter[' +
								indexOfArray +
								'][type]=' +
								filterType +
								'&filter[' +
								indexOfArray +
								'][field]=' +
								field.name +
								'&filter[' +
								indexOfArray +
								'][value]=' +
								field.value +
								'&filter[' +
								indexOfArray +
								'][comparison]=<';
							indexOfArray++;
						}
					}
				}
			});
			$.setFilterNav(
				tableID,
				property,
				form,
				displaySearchItems,
				searchItems
			);
			$.updateTable(
				tableID,
				property,
				dtTableLimit,
				dtTablePage,
				dtTableFilter,
				isSearch
			);
			$(this).remove();
		}
	);

	$(document).on(
		'click',
		'.' + $(tableID).attr('id') + '-filter-clear',
		function () {
			isSearch = 0;
			let dtTableLimit = $(
				'.' + $(tableID).attr('id') + '-dtTable-limit'
			).val();
			dtTablePage = 1;
			dtTableFilter = '';
			$('#' + $(tableID).attr('id') + '-dtTable-form').trigger('reset');
			$('.' + $(tableID).attr('id') + '-filter-button').remove();
			$('.' + $(tableID).attr('id') + '-filter-clear').remove();
			$.updateTable(
				tableID,
				property,
				dtTableLimit,
				dtTablePage,
				dtTableFilter,
				isSearch
			);
		}
	);

	$(document).on(
		'click',
		'.' + $(tableID).attr('id') + '-select-all-th',
		(e) => {
			let tagInput = $(e.currentTarget).find('input');
			if ($(e.currentTarget).hasClass('class-selected')) {
				tagInput.prop('checked', false);
				$(e.currentTarget).removeClass('class-selected');
				$('.' + $(tableID).attr('id') + '-select-td').removeClass(
					'class-selected'
				);
				$(
					'.' +
						$(tableID).attr('id') +
						'-selected-' +
						property.selectID
				).prop('checked', false);
				$('.' + $(tableID).attr('id') + '-select-td')
					.closest('tr')
					.removeClass($(tableID).attr('id') + '-add-class');
				countSelectedTD = 0;
			} else {
				tagInput.prop('checked', true);
				$(e.currentTarget).addClass('class-selected');
				$('.' + $(tableID).attr('id') + '-select-td').addClass(
					'class-selected'
				);
				$(
					'.' +
						$(tableID).attr('id') +
						'-selected-' +
						property.selectID
				).prop('checked', true);
				$('.' + $(tableID).attr('id') + '-select-td')
					.closest('tr')
					.addClass($(tableID).attr('id') + '-add-class');
				countSelectedTD = countTD;
			}
		}
	);

	$(document).on('click', '.' + $(tableID).attr('id') + '-action', (e) => {
		let target = e.currentTarget.getAttribute('data-type');
		let message = e.currentTarget.getAttribute('data-message');
		let form = $(
			'#' + $(tableID).attr('id') + '-dtTable-form-select'
		).serializeArray();
		let formData = {};
		let arrData = [];
		let dtTableLimit = $(
			'.' + $(tableID).attr('id') + '-dtTable-limit'
		).val();
		formData['field'] = property.selectID;
		$.each(form, function (i, val) {
			arrData[i] = val.value;
			formData['data'] = arrData;
		});

		let funct = target;
		url = dtURLAction[funct];
		let data = {
			url,
			formData,
			tableID,
			property,
			dtTableLimit,
			dtTablePage,
			isSearch,
			dtTableFilter,
			exportColumns,
			message,
		};
		window[funct](data);
	});
	$(document).on('click', '.' + $(tableID).attr('id') + '-select-td', (e) => {
		let tagInput = $(e.currentTarget).find('input');
		if ($(e.currentTarget).hasClass('class-selected')) {
			tagInput.prop('checked', false);
			$(e.currentTarget).removeClass('class-selected');
			$(e.currentTarget)
				.closest('tr')
				.removeClass($(tableID).attr('id') + '-add-class');
			if (property.select == true && property.multiSelect == true) {
				countSelectedTD--;
				if (countSelectedTD < countTD) {
					$('#' + $(tableID).attr('id') + '-selecting-all').prop(
						'checked',
						false
					);
					$(
						'.' + $(tableID).attr('id') + '-select-all-th'
					).removeClass('class-selected');
					$(e.currentTarget)
						.closest('tr')
						.removeClass($(tableID).attr('id') + '-add-class');
				}
			}
		} else {
			if (
				property.multiSelect == false ||
				property.multiSelect == undefined
			) {
				$(
					'.' +
						$(tableID).attr('id') +
						'-selected-' +
						property.selectID
				).prop('checked', false);
				$('.' + $(tableID).attr('id') + '-select-td').removeClass(
					'class-selected'
				);
				$('.' + $(tableID).attr('id') + '-select-td')
					.closest('tr')
					.removeClass($(tableID).attr('id') + '-add-class');
			}
			tagInput.prop('checked', true);
			$(e.currentTarget).addClass('class-selected');
			$(e.currentTarget)
				.closest('tr')
				.addClass($(tableID).attr('id') + '-add-class');
			if (property.select == true && property.multiSelect == true) {
				countSelectedTD++;
				if (countSelectedTD == countTD) {
					$('#' + $(tableID).attr('id') + '-selecting-all').prop(
						'checked',
						true
					);
					$('.' + $(tableID).attr('id') + '-select-all-th').addClass(
						'class-selected'
					);
					$('.' + $(tableID).attr('id') + '-select-td')
						.closest('tr')
						.addClass($(tableID).attr('id') + '-add-class');
				}
			}
		}
	});
	$.refreshTable = function ($ID) {
		let divID = $(`div[id="${$ID}"]`);
		let dtTableLimit = $('.' + $ID + '-dtTable-limit').val();
		$.updateTable(
			divID,
			property,
			dtTableLimit,
			dtTablePage,
			dtTableFilter,
			isSearch
		);
	};

	$(document).on('click', `.${$(tableID).attr('id')}-sort`, function () {
		let dtTableLimit = $(
			'.' + $(tableID).attr('id') + '-dtTable-limit'
		).val();
		let dataSort =
			$(this).attr('data-sort') == undefined ||
			$(this).attr('data-sort') == ''
				? 'asc'
				: $(this).attr('data-sort');
		let dataField = $(this).attr('data-field');
		property.sortName = dataField;
		property.sortOrder = dataSort;
		$.updateTable(
			tableID,
			property,
			dtTableLimit,
			dtTablePage,
			dtTableFilter,
			isSearch
		);
		$(`.${$(tableID).attr('id')}-sort`).attr('data-sort', '');
		$(`.${$(tableID).attr('id')}-sort`).addClass('text-muted');
		if (dataSort == 'desc') {
			$(this).removeClass('bx-sort-a-z');
			$(this).addClass('bx-sort-z-a');
			$(this).attr('data-sort', 'asc');
		} else if (dataSort == 'asc') {
			$(this).removeClass('bx-sort-z-a');
			$(this).addClass('bx-sort-a-z');
			$(this).attr('data-sort', 'desc');
		}
		$(this).removeClass('text-muted');
		$(this).addClass('text-primary');
	});
};

$.setTable = function (t, property, dtTablePage) {
	property.method = 'GET';
	property.dataType = 'json';
	if (typeof property.tableIsResponsive === 'undefined') {
		property.tableIsResponsive = false;
	}
	let dtTableLimit = property.pagination ? 10 : 0;
	let defaultLimit = [10, 25, 50, 100];
	if (typeof property.options !== 'undefined') {
		if (typeof property.options.currentLimit !== 'undefined') {
			dtTableLimit = property.options.currentLimit;
		}
		if (typeof property.options.limit !== 'undefined') {
			defaultLimit = property.options.limit;
			if (typeof property.options.currentLimit === 'undefined') {
				dtTableLimit = property.options.limit[0];
			}
		}
	}
	dtTableLimit = property.pagination ? dtTableLimit : 0;
	let dtTbody = '';
	let dtThead = '';
	let dtExtends = '';
	let index = 0;
	let limitSection = $.setLimit(t, defaultLimit, dtTableLimit);
	let pagination = '';
	let dtOrderHead = [];
	let searchButton = '';
	let modalSearch = '';
	let actionButton = '';

	$.each(property.buttonAction, function (i, value) {
		let act = value.action;
		actionButton =
			actionButton +
			`
        <button 
		class="btn btn-outline-primary m-2 dtale-action ${$(t).attr('id')}-action ${
				value.style == undefined ? 'btn-default' : value.style
			}" type="button" id="${$(t).attr('id')}${value.action}" title="${
				value.display
			}" data-url="${value.url}" data-type="${
				value.action
			}" data-message="${
				value.message
			}" style="display:flex;align-items:center;">
        <i class="ficon mr-25 ${
			value.icon == undefined ? '' : value.icon
		}" style="top:0px;"></i><span>${value.display}</span>
        </button>
        `;
	});
	searchButton = `<div class="row"><div class="col-sm-12 col-md-6" id="${$(
		t
	).attr('id')}-search-result">
					</div><div class="col-sm-12 col-md-6 d-flex justify-content-end align-items-center pb-1">
					${actionButton}
					</div></div>`;

	if (typeof property.search !== 'undefined' && property.search == true) {
		let modalSearchBody = '';
		let id = $(t).attr('id');
		if (typeof property.searchItems !== 'undefined') {
			$.each(property.searchItems, function (key, value) {
				modalSearchBody = modalSearchBody + '<div class="form-group">';
				if (value.type == 'text') {
					modalSearchBody =
						modalSearchBody +
						`<input type="text" placeholder="${value.display}" name="${value.name}" class="form-control mb-2" id="${id}-form-data-${value.name}" />`;
				} else if (value.type == 'number') {
					let mdMin = '';
					let mdMax = '';
					if (typeof value.min !== 'undefined') {
						mdMin = 'min="' + value.min + '"';
					}
					if (typeof value.max !== 'undefined') {
						mdMax = 'max="' + value.max + '"';
					}
					modalSearchBody =
						modalSearchBody +
						'<input type="number" name="' +
						value.name +
						'" class="form-control mb-2" ' +
						mdMin +
						' ' +
						mdMax +
						' id="' +
						$(t).attr('id') +
						'-form-data-' +
						value.name +
						'"/>';
				} else if (value.type == 'select') {
					let mdOption = `<option value="">--- Pilih ${value.display} ---</option>`;
					if (typeof value.option !== 'undefined') {
						$.each(value.option, function (key, value2) {
							mdOption =
								mdOption +
								'<option value="' +
								value2.value +
								'">' +
								value2.title +
								'</option>';
						});
					}
					// modalSearchBody = modalSearchBody + '<label>' + value.display + '</label>';
					modalSearchBody =
						modalSearchBody +
						'<select name="' +
						value.name +
						'" class="form-control mb-2" id="' +
						$(t).attr('id') +
						'-form-data-' +
						value.name +
						'">';
					modalSearchBody = modalSearchBody + mdOption + '</select>';
				} else if (value.type == 'date') {
					// modalSearchBody = modalSearchBody + '<label>' + value.display + '</label>';
					modalSearchBody = modalSearchBody + '<div class="row">';
					modalSearchBody =
						modalSearchBody +
						'<div class="col-md-6"><label>Tanggal Mulai</label><input type="date" name="' +
						value.name +
						'[start]" class="form-control mb-2" id="' +
						$(t).attr('id') +
						'-form-data-' +
						value.name +
						'start"/></div>';
					modalSearchBody =
						modalSearchBody +
						'<div class="col-md-6"><label>Tanggal Berakhir</label><input type="date" name="' +
						value.name +
						'[end]" class="form-control mb-2" id="' +
						$(t).attr('id') +
						'-form-data-' +
						value.name +
						'end" /></div>';
					modalSearchBody = modalSearchBody + '</div>';
				}
				modalSearchBody = modalSearchBody + '</div>';
			});
		}
		searchButton = `
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="${$(t).attr(
				'id'
			)}-search-result">
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-end align-items-center pb-1 flex-wrap">
            ${actionButton}
            <button class="ml-1 btn btn-primary" data-bs-toggle="modal" data-bs-target="#${$(
				t
			).attr('id')}-searchModal">Cari</button>
        </div>
        </div>`;
		modalSearch =
			modalSearch +
			'<div class="modal text-left" id="' +
			$(t).attr('id') +
			'-searchModal">';
		modalSearch = modalSearch + '<div class="modal-dialog">';
		modalSearch = modalSearch + '<div class="modal-content">';
		modalSearch = modalSearch + '<div class="modal-header">';
		modalSearch =
			modalSearch +
			'<h4 class="modal-title">' +
			(typeof property.searchTitle !== 'undefined'
				? property.searchTitle
				: 'Pencarian') +
			'</h4>';
		modalSearch =
			modalSearch +
			`
			<button type="button" data-bs-dismiss="modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			`;
		modalSearch = modalSearch + '</div>';
		modalSearch =
			modalSearch +
			'<form id="' +
			$(t).attr('id') +
			'-dtTable-form"><div class="modal-body">' +
			modalSearchBody +
			'</div>';
		modalSearch = modalSearch + '<div class="modal-footer">';
		modalSearch =
			modalSearch +
			`<button type="submit" class="btn btn-primary" style="margin-left:2px;">Cari</button>`;
		modalSearch =
			modalSearch +
			`<button type="button" data-bs-dismiss="modal" class="btn btn-light-secondary" onclick="closeModal('#${$(
				t
			).attr(
				'id'
			)}-searchModal')" style="border:margin-right:2px;">Tutup</button>
			`;
		modalSearch = modalSearch + '</div></form>';
		modalSearch = modalSearch + '</div>';
		modalSearch = modalSearch + '</div>';
		modalSearch = modalSearch + '</div>';
	}
	$.each(property.colModel, function (key, value) {
		let width = value.width == undefined ? '' : value.width;
		width = 'min-width: ' + width + ';max-width: ' + width + ';';
		if (value.sortAble == true) {
			let sortStatus = '';
			if (value.name == property.sortName) {
				if (property.sortOrder == 'desc') {
					sortStatus = `<i class="text-primary bx bx-sort-z-a ${$(
						t
					).attr('id')}-sort" data-field="${
						value.name
					}" data-sort="asc"></i>`;
				} else {
					sortStatus = `<i class="text-primary bx bx-sort-a-z ${$(
						t
					).attr('id')}-sort" data-field="${
						value.name
					}" data-sort="desc"></i>`;
				}
			} else {
				sortStatus = `<i class="text-muted bx bx-sort-a-z ${$(t).attr(
					'id'
				)}-sort" data-field="${value.name}" data-sort=""></i>`;
			}
			dtThead =
				dtThead +
				'<th title="' +
				value.display +
				'" style="text-align:' +
				(value.align == undefined ? 'left' : value.align) +
				';' +
				width +
				'">' +
				value.display +
				' ' +
				sortStatus +
				'</th>';
		} else {
			dtThead =
				dtThead +
				'<th title="' +
				value.display +
				'" style="text-align:' +
				(value.align == undefined ? 'left' : value.align) +
				';' +
				width +
				'">' +
				value.display +
				'</th>';
		}
		dtOrderHead[index++] = value;
	});
	dtTbody = '<tbody class="' + $(t).attr('id') + '-tbody"></tbody>';
	tmpdtThead = '<thead><tr>' + dtThead + '</tr></thead>';
	dtTable = `
    <section id="table-success">
        <div class="card mb-1" style="padding: 10px">
            <div class="table-responsive">
                <table class="table">
                ${tmpdtThead}
                ${dtTbody} 
                </table>
            </div>
        </div>
    </section> 
    `;
	$(t).html(dtTable);
	$(`.${$(t).attr('id')}-tbody`).html(
		`<td style="text-align:center;" colspan="${dtOrderHead.length}"><img src="/app-assets/images/icon/loading.gif" width="20"></td>`
	);
	let results = $.getData(
		t,
		property,
		dtOrderHead,
		dtTableLimit,
		dtTablePage
	);

	if (property.pagination) {
		pagination =
			'<div class="col-sm-12 col-md-6 ' +
			$(t).attr('id') +
			'-pagination">' +
			$.setPagination(property, results.pagination) +
			'</div>';
	}

	if (property.select == true && property.multiSelect == true) {
		dtThead = `<thead class="cstm-table-head"><tr><th title='select' class='${$(
			t
		).attr('id')}-select-all-th text-center'><input type='checkbox' id='${$(
			t
		).attr('id')}-selecting-all'></th>${dtThead}</tr></thead>`;
		dtTable = `
        <section id="table-success">
            <div class="card mb-1" style="padding: 10px">
                <div class="table-responsive">
                    <form id="${$(t).attr('id')}-dtTable-form-select">
                        <table class="cstm-table table m-0">${dtThead}${dtTbody}</table>
                    </form>
                </div>
            </div>
        </section> 
        `;
	} else if (property.select == true) {
		dtThead = `<thead class="cstm-table-head"><tr><th title='select' class='text-center'>#</th>${dtThead}</tr></thead>`;
		dtTable = `<form id="${$(t).attr(
			'id'
		)}-dtTable-form-select"><table class="cstm-table table">${dtThead}${dtTbody}</table></form>`;
	} else {
		dtThead =
			'<thead class="cstm-table-head"><tr>' + dtThead + '</tr></thead>';
		dtTable = `
        <section id="table-success">
            <div class="card mb-1" style="padding: 10px">
                <div class="table-responsive">
                    <table class="cstm-table table m-0">${dtThead}${dtTbody}</table>
                </div>
            </div>
        </section> 
        `;
	}

	if (property.pagination) {
		dtExtends =
			'<div class="row mt-1">' + pagination + limitSection + '</div>';
	}
	dtTable = searchButton + dtTable;

	dtTable = dtTable + dtExtends;
	dtTable += `
    <style>
	
	@media screen and (max-width: 600px) {
		#btn-container {
			display: flex;
			flex-direction: column;
		}

		#pagination-container {
			justify-content: center !important;
			margin-top: 2.5rem !important;
		}


		.cstm-table thead {
			display: none;
			height: 0;
			position: absolute;
		}

		.cstm-table tr {
			display: block;
			margin-bottom: .625em;
		}

		.cstm-table td {
			border-bottom: none;
			display: block;
			font-size: .8em;
			text-align: right !important;
			max-width: 100% !important;
		}

		.cstm-table td::before {
			/* content: attr(data-label); */
			/* content by the head */
			content: attr(data-label);
			float: left;
			font-weight: bold;
			text-transform: uppercase;
		}

		
		.cstm-table td:last-child {
			border-bottom: 5px solid;
		}
		
		.table-responsive {
			overflow-x: hidden;
		}

		#btn-container .btn {
			height: 3em;
			width: 100%;
		}
	
		
		.tb-responsive thead {
			visibility: hidden;
			height: 0;
			position: absolute;
		}

		.tb-responsive tr {
			display: block;
			margin-bottom: .625em;
		}

		.tb-responsive td {
			border: 1px solid;
			border-bottom: none;
			display: block;
			font-size: .8em;
			text-align: right;
		}

		.tb-responsive td::before {
			content: attr(data-label);
			float: left;
			font-weight: bold;
			text-transform: uppercase;
		}

		.tb-responsive td:last-child {
			border-bottom: 1px solid;
		}
		

	}
	</style>
    
    `;

	$('body').append(modalSearch);
	$(t).html(dtTable);
	$(`.${$(t).attr('id')}-tbody`).html(results.tbody);
	$('.' + $(t).attr('id') + '-count-data').text(results.countData);
	if (property.hasOwnProperty('success')) {
		property.success(results.data);
	}

	return results.data;
};

function closeModal(t) {
	$(t).modal('hide');
}

$.getData = function (
	t,
	property,
	dtOrderHead,
	dtTableLimit,
	dtTablePage,
	dtTableFilter = '',
	isSearch = 0
) {
	let url = property.url;
	let queryString =
		'?limit=' +
		dtTableLimit +
		'&page=' +
		dtTablePage +
		'&sort=' +
		property.sortName +
		'&dir=' +
		property.sortOrder +
		dtTableFilter;
	if (url.includes('?')) {
		queryString =
			'&limit=' +
			dtTableLimit +
			'&page=' +
			dtTablePage +
			'&sort=' +
			property.sortName +
			'&dir=' +
			property.sortOrder +
			dtTableFilter;
	}
	let tbody = '';
	let pagination = '';
	let countData = '';

	let data_label = [];
	let index = 0;
	$.each(dtOrderHead, function (key, value) {
		data_label[index++] = value.display;
	});

	console.log(data_label);

	let dataResponse = {};
	$.ajax({
		url: url + queryString,
		type: property.method,
		dataType: property.dataType,
		async: false,
		success: function (response) {
			if (property.pagination) {
				dataResponse = response;
				if (isSearch < 1 && response.data.pagination.total_data == 0) {
					tbody =
						'<tr><td colspan="' +
						(property.select == true
							? parseInt(dtOrderHead.length + 1)
							: dtOrderHead.length) +
						'" class="text-center">Data kosong</td></tr>';
				} else if (
					isSearch > 0 &&
					response.data.pagination.total_data == 0
				) {
					$('#table-success').hide();
					$('.table-pagination').hide();
					$('.table-count-data').hide();
					$('#dtTable-limit').hide();
					$('#data_kosong').show();
				} else {
					$('#table-success').show();
					$('.table-pagination').show();
					$('.table-count-data').show();
					$('#dtTable-limit').show();
					$('#data_kosong').hide();

					$.each(response.data.results, function (key, value) {
						tbody = tbody + '<tr>';
						if (property.select == true) {
							tbody =
								tbody +
								"<td class='" +
								$(t).attr('id') +
								"-select-td text-center' title='select' class='text-center'><input type='checkbox' name='" +
								property.selectID +
								"' class='" +
								$(t).attr('id') +
								'-selected-' +
								property.selectID +
								"' value='" +
								value[property.selectID] +
								"'></td>";
						}
						let irWidth = 0;
						$.each(dtOrderHead, function (key, field) {
							let label = '';
							data_label.forEach(function (item, index) {
								if (index == key) {
									label = item;
								}
							});

							let text = value[field.name];
							if (property.colModel.length - 1 == irWidth) {
								irWidth = 0;
							}
							if (field.render !== undefined) {
								text = field.render(text, value);
							}
							if (field.action !== undefined) {
								if (field.action.function !== undefined) {
									let data = JSON.stringify(value)
										.replace(/\'/g, "\\'")
										.replace(/\"/g, "'");
									// text = `<a href="javascript:;" onclick="${field.action.function}(${data})" class="btn btn-${field.action.class} btn-icon"><i class="${field.action.icon}"></i><a>`
									text = `<a href="javascript:;" onclick="${field.action.function}(${data})" class="${field.action.class}"><i class="${field.action.icon}"></i><a>`;
								}
								if (field.action.url !== undefined) {
									// text = `<a href="${field.action.url}" class="btn btn-${field.action.class} btn-icon"><i class="${field.action.icon}"></i><a>`
									text = `<a href="${field.action.url}" class="${field.action.class}"><i class="${field.action.icon}"></i><a>`;
								}
							}
							tbody =
								tbody +
								`<td data-label="${label}" style="vertical-align: top; text-align: ` +
								(field.align == undefined
									? 'left'
									: field.align) +
								';' +
								(property.colModel[irWidth].width == undefined
									? ''
									: 'max-width: ' +
									  property.colModel[irWidth].width) +
								';">' +
								text +
								'</td>';
							irWidth++;
						});
						tbody = tbody + '</tr>';
					});
				}
				tbody = tbody;
				pagination = response.data.pagination;
				countData =
					'Menampilkan data ' +
					response.data.pagination.start +
					' - ' +
					response.data.pagination.end +
					' dari total ' +
					response.data.pagination.total_data +
					' data';
			} else {
				dataResponse = response;
				$.each(response.data.results, function (key, value) {
					tbody = tbody + '<tr>';
					if (property.select == true) {
						tbody =
							tbody +
							"<td class='" +
							$(t).attr('id') +
							"-select-td text-center' title='select' class='text-center'><input type='checkbox' name='" +
							property.selectID +
							"' class='" +
							$(t).attr('id') +
							'-selected-' +
							property.selectID +
							"' value='" +
							value[property.selectID] +
							"'></td>";
					}
					let irWidth = 0;
					$.each(dtOrderHead, function (key, field) {
						let label = '';
						data_label.forEach(function (item, index) {
							if (index == key) {
								label = item;
							}
						});
						let text = value[field.name];
						if (property.colModel.length - 1 == irWidth) {
							irWidth = 0;
						}
						if (field.render !== undefined) {
							text = field.render(text, value);
						}
						if (field.action !== undefined) {
							if (field.action.function !== undefined) {
								let data = JSON.stringify(value)
									.replace(/\'/g, "\\'")
									.replace(/\"/g, "'");
								// text = `<a href="javascript:;" onclick="${field.action.function}(${data})" class="btn btn-${field.action.class} btn-icon"><i class="${field.action.icon}"></i><a>`
								text = `<a href="javascript:;" onclick="${field.action.function}(${data})" class="${field.action.class}"><i class="${field.action.icon}"></i><a>`;
							}
							if (field.action.url !== undefined) {
								// text = `<a href="${field.action.url}" class="btn btn-${field.action.class} btn-icon"><i class="${field.action.icon}"></i><a>`
								text = `<a href="${field.action.url}" class="${field.action.class}"><i class="${field.action.icon}"></i><a>`;
							}
						}
						tbody =
							tbody +
							`<td data-label="${label}" style="vertical-align: top; text-align: ` +
							(field.align == undefined ? 'left' : field.align) +
							';' +
							(property.colModel[irWidth].width == undefined
								? ''
								: 'max-width: ' +
								  property.colModel[irWidth].width) +
							';">' +
							text +
							'</td>';

						irWidth++;
					});
					tbody = tbody + '</tr>';
				});
				tbody = tbody;
			}
		},
	});
	return {
		tbody: tbody,
		pagination: pagination,
		countData: countData,
		data: dataResponse,
	};
};

$.setLimit = function (t, limitOptions, currentLimit) {
	let id = $(t).attr('id');
	let limit = '';
	limit += '<div class="col-sm-12 col-md-6 text-right">';
	limit += `<div class="row">`;

	limit += `<div class="col-md-9 col-6 my-auto text-right">`;
	limit += `<p class="card-text mb-0 dark ${id}-count-data">aa/p>`;
	limit += `</div>`;

	limit += `<div class="col-md-3 col-6 text-right">`;
	limit += `<fieldset class="form-group mb-0">`;
	limit +=
		'<select class="form-control mb-2 ' +
		$(t).attr('id') +
		'-dtTable-limit" id="dtTable-limit">';
	$.each(limitOptions, function (key, value) {
		limit +=
			'<option value="' +
			value +
			'" ' +
			(currentLimit == value ? 'selected' : '') +
			'>' +
			value +
			'</option>';
	});
	limit += '</select>';
	limit += `</fieldset>`;
	limit += `</div>`;

	limit += `</div>`;
	limit += `</div>`;

	return limit;
};

$.setPagination = function (property, data) {
	let pagination = '<nav >';
	pagination = pagination + '<ul class="pagination pagination-primary mb-0">';
	if (data.prev != '0') {
		pagination =
			pagination +
			'<li class="page-item previous"><a class="page-link" href="#' +
			data.prev +
			'"><</li>';
	}

	$.each(data.detail, function (key, value) {
		if (value == data.current) {
			pagination =
				pagination +
				'<li class="page-item active" aria-current="page"><a class="page-link" href="#">' +
				value +
				'</a></li>';
		} else {
			pagination =
				pagination +
				'<li class="page-item"><a class="page-link" href="#' +
				value +
				'">' +
				value +
				'</a></li>';
		}
	});

	if (data.next) {
		pagination =
			pagination +
			'<li class="page-item next"><a class="page-link" href="#' +
			data.next +
			'">></a></li>';
	}

	pagination = pagination + '</ul>';
	pagination = pagination + '</nav>';
	return pagination;
};

$.updateTable = function (
	t,
	property,
	dtTableLimit,
	dtTablePage,
	dtTableFilter,
	isSearch
) {
	property.method = 'GET';
	property.dataType = 'json';
	let dtTbody = '';
	let dtExtends = '';
	let index = 0;
	let pagination = '';
	let dtOrderHead = [];
	$.each(property.colModel, function (key, value) {
		dtOrderHead[index++] = value;
	});
	$(`.${$(t).attr('id')}-tbody`).html(
		`<td style="text-align:center;" colspan="${
			property.select == true
				? dtOrderHead.length + 1
				: dtOrderHead.length
		}"><img src="/app-assets/images/icon/loading.gif"></td>`
	);
	let results = $.getData(
		t,
		property,
		dtOrderHead,
		dtTableLimit,
		dtTablePage,
		dtTableFilter,
		isSearch
	);
	dtTbody = results.tbody;
	$(`.${$(t).attr('id')}-tbody`).html(dtTbody);
	if (property.pagination) {
		pagination = $.setPagination(property, results.pagination);
		$(`.${$(t).attr('id')}-pagination`).html(pagination);
		$('.' + $(t).attr('id') + '-count-data').text(results.countData);
	}
	$(`.${$(t).attr('id')}-select-all-th`).removeClass('class-selected');
	$(`#${$(t).attr('id')}-selecting-all`).prop('checked', false);
	if (property.hasOwnProperty('success')) {
		property.success(results.data);
	}
};

$.setFilterNav = function (t, property, form, displaySearchItems, searchItems) {
	let filterString = '';
	let dateIteration = 0;
	let dateValue = [];
	let dateFilter = '';
	$.each(form, function (i, field) {
		if (displaySearchItems[field.name] !== undefined) {
			if (field.value != '') {
				if (searchItems[field.name] == 'select') {
					filterString =
						filterString +
						"<button type='button' class='btn btn-info mr-25 " +
						$(t).attr('id') +
						"-filter-button' value='" +
						field.name +
						"'>" +
						displaySearchItems[field.name] +
						' : ' +
						displaySearchItems[field.name + field.value] +
						'</button>';
				} else if (searchItems[field.name] == 'date') {
					dateValue[dateIteration] = field.value;
					dateIteration++;
					if (dateIteration > 1) {
						let dateAtribut =
							' date-start = "' +
							dateValue[0] +
							'" date-end = "' +
							dateValue[1] +
							'" ';
						dateFilter = dateValue.join(' - ');
						let filterName = field.name;
						filterName = filterName.replace('[end]', '');
						dateIteration = 0;
						filterString =
							filterString +
							"<button type='button' class='btn btn-info mr-25 " +
							$(t).attr('id') +
							"-filter-button' value='" +
							filterName +
							"'" +
							dateAtribut +
							'>' +
							displaySearchItems[field.name] +
							' : ' +
							dateFilter +
							'</button>';
					}
				} else {
					filterString =
						filterString +
						"<button type='button' class='btn btn-info mr-25 " +
						$(t).attr('id') +
						"-filter-button' value='" +
						field.name +
						"'>" +
						displaySearchItems[field.name] +
						' : ' +
						field.value +
						'</button>';
				}
			}
		}
	});
	if (filterString != '') {
		filterString =
			filterString +
			'  ' +
			'<button type="button" data-bs-dismiss="modal" class="btn btn-info ' +
			$(t).attr('id') +
			'-filter-clear">Clear All</button>';
	}
	$('#' + $(t).attr('id') + '-search-result').html(filterString);
};

$.ajaxActionButtons = function (data) {
	$.ajax({
		url: data.url,
		method: 'POST',
		data: data.formData,
		success: function (response) {
			swal.hideLoading();
			let $message = '';
			let title = '';
			let icon = '';
			if (response.message != undefined) {
				$message = response.message;
			}
			if (response.data.success > 0 && response.data.failed == 0) {
				title = 'Berhasil!';
				$message =
					$message +
					'<br>Success : ' +
					response.data.success +
					'. Gagal : ' +
					response.data.failed;
				icon = 'success';
				$.refreshTable($(data.tableID).attr('id'));
			} else if (response.data.success == 0 && response.data.failed > 0) {
				title = 'Gagal!';
				$message =
					$message +
					'<br>Success : ' +
					response.data.success +
					'. Gagal : ' +
					response.data.failed;
				icon = 'error';
				$.refreshTable($(data.tableID).attr('id'));
			} else {
				title = 'Berhasil!';
				$message =
					$message +
					'<br>Success : ' +
					(response.data.success == undefined
						? 0
						: response.data.success) +
					'. Gagal : ' +
					(response.data.failed == undefined
						? 0
						: response.data.failed);
				icon = 'warning';
				$.refreshTable($(data.tableID).attr('id'));
			}
			try {
				Swal.fire({
					title: title,
					html: $message,
					icon: icon,
				});
			} catch (error) {
				alert('Berhasil! ' + $message);
			}
		},
		error: function (err) {
			let response = err.responseJSON;
			if (response.message == 'Unauthorized' && response.status == 403) {
				try {
					Swal.fire(
						'Gagal!',
						'Sesi sudah habis. Silahkan login terlebih dahulu.',
						'error'
					);
				} catch (error) {
					alert(
						'Gagal! Sesi sudah habis. Silahkan login terlebih dahulu.'
					);
				}
			} else {
				let $message = '';
				if (response.message != undefined) {
					$message = response.message;
				}
				try {
					Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
				} catch (error) {
					alert('Gagal! Terjadi kesalahan sistem.');
				}
			}
		},
	});
	$.refreshTable($(data.tableID).attr('id'));
};

function remove(data) {
	if (data.formData['data'] == undefined) {
		Swal.fire('Perhatian!', 'Tidak ada data yang dipilih', 'warning');
		return false;
	}
	let message = data.message === 'undefined' ? 'menghapus' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan ' + message + ' data yang dipilih?',
		icon: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ok',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			showLoadProcess();

			setTimeout(() => {
				$.ajaxActionButtons(data);
			}, 300);
		}
	});
}

function active(data) {
	if (data.formData['data'] == undefined) {
		Swal.fire('Perhatian!', 'Tidak ada data yang dipilih', 'warning');
		return false;
	}
	let message = data.message === 'undefined' ? 'mengaktifkan' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan ' + message + ' data yang dipilih?',
		icon: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ok',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			showLoadProcess();

			setTimeout(() => {
				$.ajaxActionButtons(data);
			}, 300);
		}
	});
}

function nonactive(data) {
	if (data.formData['data'] == undefined) {
		Swal.fire('Perhatian!', 'Tidak ada data yang dipilih', 'warning');
		return false;
	}
	let message = data.message === 'undefined' ? 'nonaktifkan' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan ' + message + ' data yang dipilih?',
		icon: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ok',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			showLoadProcess();

			setTimeout(() => {
				$.ajaxActionButtons(data);
			}, 300);
		}
	});
}

function accept(data) {
	if (data.formData['data'] == undefined) {
		Swal.fire('Perhatian!', 'Tidak ada data yang dipilih', 'warning');
		return false;
	}
	let message = data.message == 'undefined' ? 'Accept' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan ' + message + ' data yang dipilih?',
		icon: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ok',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			showLoadProcess();

			setTimeout(() => {
				$.ajaxActionButtons(data);
			}, 300);
		}
	});
}

function reject(data) {
	if (data.formData['data'] == undefined) {
		Swal.fire('Perhatian!', 'Tidak ada data yang dipilih', 'warning');
		return false;
	}
	let message = data.message === 'undefined' ? 'Reject' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan ' + message + ' data yang dipilih?',
		icon: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ok',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			showLoadProcess();

			setTimeout(() => {
				$.ajaxActionButtons(data);
			}, 300);
		}
	});
}

function process(data) {
	let message = data.message == 'undefined' ? 'Proses' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan ' + message + '?',
		icon: 'warning',
		showCancelButton: true,
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ok',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			showLoadProcess();

			setTimeout(() => {
				$.ajaxActionButtons(data);
			}, 300);
		}
	});
}

function exportExcel(data) {
	$('body')
		.append(
			$('<form/>')
				.attr({
					action: data.url + '?' + data.dtTableFilter.slice(1),
					method: 'post',
					id: 'dtexport-excel-form',
				})
				.append(
					$('<input/>').attr({
						type: 'hidden',
						name: 'columns',
						value: JSON.stringify(data.exportColumns['columns']),
					})
				)
				.append(
					$('<input/>').attr({
						type: 'hidden',
						name: 'display',
						value: JSON.stringify(data.exportColumns['display']),
					})
				)
				.append(
					$('<input/>').attr({
						type: 'hidden',
						name: 'align',
						value: JSON.stringify(data.exportColumns['align']),
					})
				)
				.append(
					$('<input/>').attr({
						type: 'hidden',
						name: 'sort',
						value: JSON.stringify(data.property.sortName),
					})
				)
				.append(
					$('<input/>').attr({
						type: 'hidden',
						name: 'dir',
						value: JSON.stringify(data.property.sortOrder),
					})
				)
		)
		.find('#dtexport-excel-form')
		.submit();
	$('#dtexport-excel-form').remove();
}

$.countTD = function (tableID, property) {
	if (property.select == true) {
		if (property.tableIsResponsive == true) {
			return tableID
				.children('div')
				.children('form')
				.children('table')
				.children('tbody')
				.children('tr').length;
		} else {
			return tableID
				.children('form')
				.children('table')
				.children('tbody')
				.children('tr').length;
		}
	} else {
		if (property.tableIsResponsive == true) {
			return tableID
				.children('div')
				.children('table')
				.children('tbody')
				.children('tr').length;
		} else {
			return tableID.children('table').children('tbody').children('tr')
				.length;
		}
	}
};

function showLoadProcess() {
	Swal.fire({
		text: 'data sedang diproses...',
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		didOpen: () => {
			swal.showLoading();
		},
	});
}

$('body').on('shown.bs.modal', '#table-searchModal', () => {
	let marginTop =
		window.innerHeight / 2 -
		$('#table-searchModal').find('.modal-content').height() / 2;
	$('#table-searchModal')
		.find('.modal-dialog')
		.animate(
			{
				marginTop: marginTop,
			},
			500,
			function () {
				// Animation complete.
			}
		);
});
