<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bill;
use App\Helpers\EnumHelper;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Amount;
use App\QueryOptions\Sort\DueDate;
use App\Services\BillFieldService;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Bill\BillEditRequest;
use App\Http\Requests\Bill\BillShowRequest;
use App\Http\Requests\Bill\BillStoreRequest;
use App\Http\Requests\Bill\BillDeleteRequest;
use App\Http\Requests\Bill\BillUpdateRequest;

/**
 * @see \App\Observers\BillObserver
 */
class BillController extends Controller
{
    public $billFieldService;

    public function __construct(BillFieldService $billFieldService)
    {
        $this->billFieldService = $billFieldService;
    }

    public function store(BillStoreRequest $request)
    {
        $bill = Auth::user()->bills()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $sortFields = $this->billFieldService->getSortFields();
        $searchTerm = $request->input('searchTerm');

        $query = Auth::user()->bills()->getQuery();

        $query = Pipeline::send($query)
            ->through([DueDate::class, Amount::class, Status::class])
            ->thenReturn();

        $query->when($searchTerm, function ($query, $searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query
                    ->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });

            $query->orderByRaw(
                "
                        CASE
                            WHEN title LIKE ? AND description LIKE ? THEN 1
                            WHEN title LIKE ? THEN 2
                            WHEN description LIKE ? THEN 3
                            ELSE 4
                        END
                        ",
                [
                    "%$searchTerm%",
                    "%$searchTerm%",
                    "%$searchTerm%",
                    "%$searchTerm%",
                ]
            );
        });

        $bills = $query->paginate(10);

        $filterFields = $this->billFieldService->getFilterFields();

        return view('bills.index', compact('bills', 'searchTerm', 'sortFields', 'filterFields'));
    }

    public function show(BillShowRequest $request, Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

    public function update(BillUpdateRequest $request, Bill $bill)
    {
        $bill->update($request->validated());

        return redirect()->back();
    }

    public function destroy(BillDeleteRequest $request, Bill $bill)
    {
        $bill->delete();

        if (preg_match('/\/bills\/\d+$/', URL::previous())) { // if the route the bill gets deleted by clicking on button from 'show', then the user is redirected to 'index'
            return redirect()->route('bills.index');
        }
        return redirect()->back();
    }

    public function create()
    {
        return view('bills.create');
    }

    public function edit(BillEditRequest $request, Bill $bill)
    {
        $textFields = $this->billFieldService->getTextFields($bill);
        $calendarFields = $this->billFieldService->getCalendarFields($bill);
        $selectFields = $this->billFieldService->getSelectFields($bill);

        return view('bills.edit', [
            'bill' => $bill,
            'textFields' => $textFields,
            'calendarFields' => $calendarFields,
            'selectFields' => $selectFields,
        ]);
    }
}
