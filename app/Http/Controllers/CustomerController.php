<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Displays a paginated list of customer resources.
     *
     * @throws \Throwable
     */
    #[QueryParameter('page', description: 'The page number to retrieve.', default: 1)]
    #[QueryParameter('sort', description: 'The field to sort by.', default: 'id')]
    #[QueryParameter('direction', description: 'The sorting direction.', default: self::DEFAULT_SORT_DIRECTION, example: 'desc')]
    #[QueryParameter('per_page', description: 'The number of items per page.', default: self::PER_PAGE, example: 5)]
    public function index(Request $request): JsonResponse
    {
        $response = [];

        $customersQuery = Customer::query();

        if ($request->has('sort')) {
            $sortField = $request->input('sort');

            $requestSortDirection = $request->has('direction') ? $request->input('direction') : self::DEFAULT_SORT_DIRECTION;
            $sortDirection = in_array($requestSortDirection, ['asc', 'desc']) ? $requestSortDirection : self::DEFAULT_SORT_DIRECTION;

            $customersQuery->orderBy($sortField, $sortDirection);

            $response['sort'] = [
                'field' => $request->input('sort'),
                'direction' => $request->has('direction') ? $request->input('direction') : self::DEFAULT_SORT_DIRECTION,
            ];
        }

        $customers = $customersQuery->paginate($request->input('page', self::PER_PAGE));

        $response['page'] = [
            'current_page' => $customers->currentPage(),
            'per_page' => $customers->perPage(),
            'total' => $customers->total(),
            'last_page' => $customers->lastPage(),
        ];

        $response['_embedded']['customers'] = $customers->toResourceCollection();
        $response['_links'] = $this->generateCollectionLinks($customers, 'customer');

        return response()->json($response);
    }

    /**
     * Creates a new customer resource.
     */
    public function store(CustomerRequest $request): CustomerResource
    {
        return new CustomerResource(Customer::create($request->validated()));
    }

    /**
     * Displays the specified customer resource.
     */
    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    /**
     * Updates the specified customer resource.
     */
    public function update(CustomerRequest $request, Customer $customer): CustomerResource
    {
        $customer->update($request->validated());

        return new CustomerResource($customer);
    }

    /**
     * Deletes the specified customer resource.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully',
            '_links' => [
                'list' => [
                    'href' => route('customers.index'),
                ],
                'create' => [
                    'method' => 'POST',
                    'href' => route('customers.store'),
                ],
            ],
        ]);
    }
}
