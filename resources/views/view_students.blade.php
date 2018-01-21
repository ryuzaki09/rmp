<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome to RMP</title>
        <style>
            @import url(//fonts.googleapis.com/css?family=Lato:700);

            body {
                margin:0;
                font-family:'Lato', sans-serif;
                text-align:center;
                color: #999;
            }

            .header {
                width: 100%;
                left: 0px;
                top: 5%;
                text-align: left;
                border-bottom: 1px  #999 solid;
            }

            .student-table{
                width:100%;  
            }

            table.student-table th{
                background-color: #C6C6C6;
                text-align: left;
                color: white;
                padding:7px 3px;
                font-weight: 700;
                font-size: 18px;
            }

            table.student-table tr.odd {
                text-align: left;
                padding:5px;
                background-color: #F9F9F9;
            }

            table.student-table td{
                text-align: left;
                padding:5px;
            }

            a, a:visited {
                text-decoration:none;
                color: #999;
            }

            h1 {
                font-size: 32px;
                margin: 16px 0 0 0;
            }
        </style>
        <script src="/js/app.js"></script>
    </head>

    <body>
        
        <form method="POST" action="/exportStudents">
            <div class="header">
                <div><img src="/images/RMP_logo_sm.jpg" alt="RMP Logo" title="RMP logo"></div>
                <div  style='margin: 10px;  text-align: left'>
                    {{ csrf_field() }}
                    <input type="button" name="selectAll" value="Select All" />
                    <input type="button" name="exportCourses" value="Export All Courses" />
                    <input type="submit" name="export" value="Export Selected Students"/>
                </div>
            </div>

            <div style='margin: 10px; text-align: center;'>
                <table class="student-table">
                    <tr>
                        <th></th>
                        <th>Forename</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>University</th>
                        <th>Course</th>
                    </tr>

                    @if(  count($students) > 0 )
                        @foreach($students as $student)
                        <tr>
                            <td><input type="checkbox" name="studentIds[]" value="{{$student['id']}}"></td>
                            <td style=' text-align: left;'>{{$student['firstname']}}</td>
                            <td style=' text-align: left;'>{{$student['surname']}}</td>
                            <td style=' text-align: left;'>{{$student['email']}}</td>
                            <td style=' text-align: left;'>{{$student['course']['university']}}</td>
                            <td style=' text-align: left;'>{{$student['course']['course_name']}}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center">Oh dear, no data found.</td>
                        </tr>
                    @endif
                </table>
            </div>

        </form>
        <script>
            var checked = false;

            $('input[name=selectAll]').on('click', function(){
                if (!checked) {
                    checked = true;
                    var select = true;
                } else {
                    checked = false;
                    var select = false;
                }

                var checkboxes = document.getElementsByName('studentId');
                for(var i=0, n=checkboxes.length;i<n;i++) {
                    // checkboxes[i].attr('checked', 'checked'); 
                    checkboxes[i].checked = select;
                }
            });

            $('input[name=exportCourses]').on('click', function(e) {
                e.preventDefault();
                window.location.href="/exportCourses";
            });

        </script>
        
        
    </body>

</html>
