 const input = $("#searchMenu");
 const ul = $("#taskSideMenuList");
	  function filterList() {
	    const filterValue = input.val().toLowerCase();

	    $(".clients_list_project").removeClass('collapsed');
        $(".client_project_list").addClass('show');
        $(".clients_list_project").removeClass('collapsed');
        $(".custom_expand").addClass('show');

	    ul.find("li").each(function() {
	      const text = $(this).text().toLowerCase();
	      const anchor = $(this).find("a");
	      if (text.includes(filterValue)) {
	        $(this).show();
	      } else {
	        $(this).hide();
	      }
	    });
	    
	    if(input.val() == ''){
		 	$(".clients_list_project").addClass('collapsed');
			$(".client_project_list").removeClass('show');
			$(".clients_list_project").addClass('collapsed');
			$(".custom_expand").removeClass('show');
        }
	  }
input.keyup(filterList);
