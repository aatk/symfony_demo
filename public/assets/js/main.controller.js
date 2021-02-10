let baseurl = document.location.protocol + "//" + document.location.host;

let main = {
    
    lastid: 0,
    limit : 10,
    
    nowuser: {
        id        : 0,
        firstname : "",
        secondname: "",
        surname   : "",
    },
    
    freeNowUser: function ()
    {
        main.nowuser.id         = 0;
        main.nowuser.firstname  = "";
        main.nowuser.secondname = "";
        main.nowuser.surname    = "";
    },
    
    formFillNowUser: function ()
    {
        $("#id").val(main.nowuser.id);
        $("#firstname").val(main.nowuser.firstname);
        $("#secondname").val(main.nowuser.secondname);
        $("#surname").val(main.nowuser.surname);
    },
    
    
    loaduserslist: function (data)
    {
        if (data.length < main.limit)
        {
            $('#more').hide();
        }
        else
        {
            $('#more').show();
        }
        
        $.each(data, function (key, val)
        {
            main.lastid  = parseInt(val["id"]);
            main.nowuser = val;
            main.InsertUserInList();
        });
        
        $('.userclick').off("click").on("click", function (object)
        {
            main.onUserClick(object);
        });
        $('.deluser').off("click").on("click", function (object)
        {
            main.onUserDelete(object);
        });
    },
    
    UpdateUserInList: function ()
    {
        let selector = '.user[data-userid="' + main.nowuser.id + '"]';
        $(selector).find(".firstname").html(main.nowuser.firstname);
        $(selector).find(".secondname").html(main.nowuser.secondname);
        $(selector).find(".surname").html(main.nowuser.surname);
    },
    
    InsertUserInList: function ()
    {
        let item = [];
        item.push("<th class='userclick' scope='row' data-userid='" + main.nowuser.id + "'>" + main.nowuser.id + "</th>");
        item.push("<td class='userclick firstname' data-userid='" + main.nowuser.id + "'>" + main.nowuser.firstname + "</td>");
        item.push("<td class='userclick secondname' data-userid='" + main.nowuser.id + "'>" + main.nowuser.secondname + "</td>");
        item.push("<td class='userclick surname' data-userid='" + main.nowuser.id + "'>" + main.nowuser.surname + "</td>");
        item.push("<td class='deluser' data-userid='" + main.nowuser.id + "'><a type=\"button\" class=\"btn btn-outline-danger\">Del</a></td>");
        
        $("<tr/>", {
            "class"      : "user",
            "data-userid": main.nowuser.id,
            html         : item.join("")
        }).appendTo("tbody");
    },
    
    DeleteUserFromList: function ()
    {
        let selector = '.user[data-userid="' + main.nowuser.id + '"]';
        $(selector).remove();
    },
    
    FreeUsersTable: function ()
    {
        $("tbody").empty();
        
        main.freeNowUser();
        main.lastid = 0;
    },
    
    
    FullTextFind: function ()
    {
        let lastid = main.lastid + 1;
        let find   = $("#findinput").val();
        
        if (find === "")
        {
            //Ищем как обычно
            main.FreeUsersTable();
            main.onMoreClick();
        }
        else
        {
            let url = baseurl + '/api/search/' + find + '/' + lastid + '/' + main.limit;
            $.getJSON(url, function (data)
            {
                main.loaduserslist(data);
                $('#more').off("click").on("click", main.FullTextFind);
            });
        }
    },
    
    /** EVENTS **/
    
    onMoreClick: function ()
    {
        let lastid = main.lastid + 1;
        let url    = baseurl + '/api/list/' + lastid + '/' + main.limit;
        
        $.getJSON(url, function (data)
        {
            main.loaduserslist(data);
            $('#more').off("click").on("click", main.onMoreClick);
        });
    },
    
    onFindUsers: function ()
    {
        main.FreeUsersTable();
        main.FullTextFind();
    },
    
    onUserClick: function (event)
    {
        let id  = $(event.currentTarget).attr("data-userid");
        let url = baseurl + '/api/get/' + id;
        
        $.getJSON(url, function (data)
        {
            main.nowuser.id         = parseInt(data[0].id);
            main.nowuser.firstname  = data[0].firstname;
            main.nowuser.secondname = data[0].secondname;
            main.nowuser.surname    = data[0].surname;
            
            main.formFillNowUser();
            
            $('.modal').modal('show');
        });
    },
    
    onUserChange: function (event)
    {
        
        let type = "PUT";
        let url  = baseurl + '/api/';
        let form = $('#userform').serializeArray();
        let json = [];
        
        $.each(form, function (key, val)
        {
            main.nowuser[val.name] = val.value;
        });
        json.push(main.nowuser);
        
        if (parseInt(main.nowuser.id) === 0)
        {
            type = "POST";
        }
        
        $.ajax({
            type      : type,
            url       : url + type.toLowerCase(),
            data      : JSON.stringify(json),
            statusCode: {
                201: function (data)
                {
                    if (type === "PUT")
                    {
                        main.UpdateUserInList();
                    }
                    else
                    {
                        main.nowuser = data[0];
                        main.InsertUserInList();
                    }
                    main.freeNowUser();
                    $('.modal').modal('hide');
                },
                404: function ()
                {
                    console.log('Ошибка!');
                }
            }
        });
    },
    
    onUserAdd: function ()
    {
        main.freeNowUser();
        main.formFillNowUser();
        $('.modal').modal('show');
    },
    
    onUserDelete: function (event)
    {
        let id        = $(event.currentTarget).attr("data-userid");
        let url       = baseurl + '/api/delete';
        //id
        let jsonArray = [];
        jsonArray.push({'id': id});
        
        main.freeNowUser();
        main.nowuser.id = id;
        
        $.ajax({
            type      : "DELETE",
            url       : url,
            data      : JSON.stringify(jsonArray),
            statusCode: {
                201: function (data)
                {
                    main.DeleteUserFromList();
                    main.freeNowUser();
                },
                404: function ()
                {
                    console.log('Ошибка!');
                }
            }
        });
        
    },
    
    onReady: function ()
    {
        $('#more').off("click").on("click", main.onMoreClick);
        main.onMoreClick();
        $('#adduser').on("click", main.onUserAdd);
        $('#findusers').on("click", main.onFindUsers);
        $('#savechange').on("click", function (event)
        {
            main.onUserChange(event)
        });
    }
};

$(document).ready(function ()
{
    main.onReady();
});