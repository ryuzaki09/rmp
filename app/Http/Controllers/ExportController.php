<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Course;
use App\Http\Helpers\FileExporter;

class ExportController extends Controller
{
    public function __construct()
    {

    }

    public function welcome()
    {
        return view('hello');
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {
        $students = Students::with('course')->get();
        return view('view_students', compact(['students']));
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV(Request $request)
    {
        $Ids = $request->input("studentIds");
        try {

            if (empty($Ids)) {
                throw new \Exception("No Id's selected");
            }

            $students = Students::find($Ids);
            $result = $students->toArray();
            // error_log("result: ".var_export($result, true));

            $headers = array("id", "FirstName", "LastName", "Email", "Nationality");

            $csvData = array();

            if (!empty($result)) {
                foreach ($result as $data) {
                    $csvData[] = array(
                        $data['id'],
                        $data['firstname'],
                        $data['surname'],
                        $data['email'],
                        $data['nationality']
                    );
                }
            }

            $fileExporter = new FileExporter("csv");
            $fileExporter->setHeaders($headers);
            $fileExporter->setData($csvData);
            $fileExporter->setFileName("students");
            $fileExporter->export();
        } catch(\Exception $e) {
            error_log("error: ".$e->getMessage());
            return redirect("/view");
        }
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendenceToCSV()
    {
        $courses = Course::with('students')->get();

        $result = $courses->toArray();
        // error_log('course: '.var_export($result, true));

        $csvData = array();

        if (!empty($result)) {
            foreach ($result as $data) {
                $csvData[] = array(
                    $data['id'],
                    $data['course_name'],
                    $data['university'],
                    count($data['students'])
                );
            }
        }

        try {
            $headers = array("id", "Course Name", "University", "No. of Students");
            $fileExporter = new FileExporter("csv");
            $fileExporter->setHeaders($headers);
            $fileExporter->setData($csvData);
            $fileExporter->setFileName("Courses");
            $fileExporter->export();
        } catch(\Exception $e) {
            error_log("error: ".$e->getMessage());
            return redirect("/view");
        }
    }
}
