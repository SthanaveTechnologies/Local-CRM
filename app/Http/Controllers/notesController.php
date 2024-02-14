<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Colleges;
use App\Models\Courses;
use App\Models\References;
use App\Models\Subjects;
use App\Models\Units;
use App\Models\Topics;
use Illuminate\Support\Facades\DB;

class notesController extends Controller
{

    // get data
    public function getColleges(Request $request)
    {
        $data = Colleges::where('active', 1)->orderBy('created_at', 'ASC')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Colleges data',
            'error' => false,
            'data' => $data
        ]);
    }

    public function getCourses(Request $request)
    {
        $data = Courses::where('active', 1)->orderBy('created_at', 'ASC')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Courses data',
            'error' => false,
            'data' => $data
        ]);
    }

    public function getSubjectsByCourseId(Request $request)
    {
        if ($request->courseId) {
            if (Courses::where('id', $request->courseId)->count() <= 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Course you are searching not found',
                    'error' => true
                ]);
            }

            $data = Subjects::where([
                'active' => 1,
                'course' => $request->courseId
            ])->orderBy('created_at', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'Subjects fetched',
                'error' => false,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Course Id required',
                'error' => true
            ]);
        }
    }

    public function getUnitsBySubjectId(Request $request)
    {
        if ($request->subjectId) {
            if (Subjects::where('id', $request->subjectId)->count() <= 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Subject you are searching not found',
                    'error' => true
                ]);
            }

            $data = Units::where([
                'active' => 1,
                'subject' => $request->subjectId
            ])->orderBy('name', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'Units fetched',
                'error' => false,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Subject Id required',
                'error' => true
            ]);
        }
    }

    public function getTopicsByUnitId(Request $request)
    {
        if ($request->unitId) {
            if (Units::where('id', $request->unitId)->count() <= 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Unit you are searching not found',
                    'error' => true
                ]);
            }

            $data = Topics::where([
                'active' => 1,
                'unit' => $request->unitId
            ])->orderBy('created_at', 'ASC')->get()->map(function ($item) {
                $item['references'] = $item->references;
                return $item;
            });

            return response()->json([
                'status' => 200,
                'message' => 'All topics fetched',
                'error' => false,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Unit Id required',
                'error' => true
            ]);
        }
    }

    public function getReferencesByTopicId(Request $request)
    {
        if ($request->topicId) {
            if (Topics::where('id', $request->topicId)->count() <= 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Topic you are searching not found',
                    'error' => true
                ]);
            }

            $data = References::where([
                'active' => 1,
                'topic' => $request->topicId
            ])->orderBy('created_at', 'ASC')->get();

            return response()->json([
                'status' => 200,
                'message' => 'All references for the topic fetched',
                'error' => false,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Topic Id required',
                'error' => true
            ]);
        }
    }

    // store data
    public function storeCollege(Request $request)
    {
        try {
            $status = Colleges::create([
                'id' => Str::uuid()->toString(),
                'name' => $request->collegeName,
                'image' => $request->image,
                'created_at' => Carbon::now()
            ]);

            if ($status) {
                return response()->json([
                    'status' => 200,
                    'message' => 'New college saved',
                    'error' => false
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th || 'Looks something not good',
                'error' => true,
            ]);
        }
    }

    public function storeCourse(Request $request)
    {
        if (!$request->courseName) {
            return response()->json([
                'status' => 404,
                'message' => 'Course Name required',
                'error' => true
            ]);
        }
        try {
            $status = Courses::create([
                'id' => Str::uuid()->toString(),
                'name' => $request->courseName,
                'created_at' => Carbon::now()
            ]);

            if ($status) {
                return response()->json([
                    'status' => 200,
                    'message' => 'New course added',
                    'error' => false
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th || 'Looks something not good',
                'error' => true,
            ]);
        }
    }

    public function storeSubject(Request $request)
    {
        if (!$request->subjectName || !$request->courseId) {
            return response()->json([
                'status' => 404,
                'message' => 'One or more field required',
                'error' => true
            ]);
        }
        if (Courses::where('id', $request->courseId)->count() <= 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Course you are searching not found',
                'error' => true
            ]);
        }
        try {
            $status = Subjects::create([
                'id' => Str::uuid()->toString(),
                'name' => $request->subjectName,
                'course' => $request->courseId,
                'created_at' => Carbon::now()
            ]);

            if ($status) {
                return response()->json([
                    'status' => 200,
                    'message' => 'New subject added for this course',
                    'error' => false
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th || 'Looks something not good',
                'error' => true,
            ]);
        }
    }

    public function storeUnit(Request $request)
    {
        if (!$request->unitName || !$request->subjectId) {
            return response()->json([
                'status' => 404,
                'message' => 'One or more field required',
                'error' => true
            ]);
        }
        if (Subjects::where('id', $request->subjectId)->count() <= 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Subject you are searching not found',
                'error' => true
            ]);
        }
        try {
            $status = Units::create([
                'id' => Str::uuid()->toString(),
                'name' => $request->unitName,
                'subject' => $request->subjectId,
                'created_at' => Carbon::now()
            ]);

            if ($status) {
                return response()->json([
                    'status' => 200,
                    'message' => 'New unit added for this subject',
                    'error' => false
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th || 'Looks something not good',
                'error' => true,
            ]);
        }
    }

    public function storeTopics(Request $request)
    {
        if (!$request->unitId || !$request->topicName) {
            return response()->json([
                'status' => 404,
                'message' => 'One or more field required',
                'error' => true
            ]);
        }
        if (Units::where('id', $request->unitId)->count() <= 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Unit you are searching not found',
                'error' => true
            ]);
        }
        try {
            $id = Str::uuid()->toString();
            $status = Topics::create([
                'id' => $id,
                'name' => $request->topicName,
                'unit' => $request->unitId,
                'created_at' => Carbon::now()
            ]);

            if ($request->references && count($request->references)) {

                $type = DB::table('url_type')->where('name', 'url')->value('id');

                foreach ($request->references as $value) {
                    References::create([
                        'id' => Str::uuid()->toString(),
                        'url' => $value,
                        'type' => $type,
                        'topic' => $id,
                        'created_at' => Carbon::now(),
                    ]);
                }
            }

            if ($status) {
                return response()->json([
                    'status' => 200,
                    'message' => 'New topic added for this unit',
                    'error' => false
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th || 'Looks something not good',
                'error' => true,
            ]);
        }
    }

    public function storeReferences(Request $request)
    {
        if (!$request->topicId || !$request->references) {
            return response()->json([
                'status' => 404,
                'message' => 'One or more field required',
                'error' => true
            ]);
        }
        if ($request->references && count($request->references)) {

            $type = DB::table('url_type')->where('name', 'url')->value('id');

            foreach ($request->references as $value) {
                References::create([
                    'id' => Str::uuid()->toString(),
                    'url' => $value,
                    'type' => $type,
                    'topic' => $request->topicId,
                    'created_at' => Carbon::now(),
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'References added to this topics',
                'error' => false
            ]);
        }
    }
}
