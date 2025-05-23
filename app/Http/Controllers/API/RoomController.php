<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSpecialRateRequest;
use App\Http\Requests\DateRangeRequest;
use App\Http\Requests\UpdateRoomPriceRequest;
use App\Http\Resources\ListingResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\UnavailableDateResource;
use App\Services\RoomRetrievalService;
use App\Services\RoomUpdateService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private RoomRetrievalService $roomRetrievalService;

    private RoomUpdateService $roomUpdateService;

    public function __construct(RoomRetrievalService $roomRetrievalService, RoomUpdateService $roomUpdateService)
    {
        $this->roomRetrievalService = $roomRetrievalService;
        $this->roomUpdateService = $roomUpdateService;
    }

    /**
     * Get All Rooms
     *
     * Retrieves a collection of all rooms available.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllRooms()
    {
        return RoomResource::collection($this->roomRetrievalService->getAllRooms());
    }

    /**
     * Get Room
     *
     * Retrieves details for a specific room by ID. This includes room details, availability, and pricing for a given date range.
     * The date range is used to calculate any special rates or adjustments to the standard room price.
     *
     * @return RoomResource
     */
    public function getRoom(DateRangeRequest $request, string $id)
    {
        return new RoomResource($this->roomRetrievalService->getRoomDetails($id, $request->validated()));
    }

    /**
     * Get Room Listing
     *
     * Retrieves the listing details associated with a specific room.
     *
     * @return ListingResource
     */
    public function getRoomListing(string $id)
    {
        return new ListingResource($this->roomRetrievalService->getRoomListing($id));
    }

    /**
     * Get Unavailable Dates
     *
     * Retrieves a collection of dates when the specified room is unavailable within a given date range.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getUnavailableDates(DateRangeRequest $request, string $id)
    {
        $unavailableDates = $this->roomRetrievalService->getUnavailableDatesFromRange($id, $request->validated('start_date'), $request->validated('end_date'));

        return UnavailableDateResource::collection($unavailableDates);
    }

    /**
     * Add Special Rate
     *
     * Adds a special rate for a room for a specified date range.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSpecialRate(CreateSpecialRateRequest $request, string $id)
    {
        $room = $this->roomUpdateService->addSpecialRate($id, $request->validated());

        return response()->json([
            'message' => 'Special rate added successfully.',
            'room' => new RoomResource($room),
        ]);
    }

    /**
     * Update Special Rate
     *
     * Updates an existing special rate for a room.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSpecialRate(CreateSpecialRateRequest $request, string $id)
    {
        $room = $this->roomUpdateService->updateSpecialRate($id, $request->validated());

        return response()->json([
            'message' => 'Special rate updated successfully.',
            'room' => new RoomResource($room),
        ]);
    }

    /**
     * Remove Special Rate
     *
     * Removes a special rate from a room.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeSpecialRate(Request $request, string $id)
    {
        $room = $this->roomUpdateService->removeSpecialRate($id, $request->special_rate_id);

        return response()->json([
            'message' => 'Special rate removed successfully.',
            'room' => new RoomResource($room),
        ]);
    }

    /**
     * Block Dates
     *
     * Blocks a range of dates for a room, making it unavailable for booking.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockDates(DateRangeRequest $request, string $id)
    {
        $room = $this->roomUpdateService->blockDates($id, $request->validated());

        return response()->json([
            'message' => 'Room dates blocked successfully.',
            'room' => new RoomResource($room),
        ]);
    }

    /**
     * Unblock Dates
     *
     * Unblocks a previously blocked range of dates for a room, making it available for booking again.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function unblockDates(DateRangeRequest $request, string $id)
    {
        $room = $this->roomUpdateService->unblockDates($id, $request->validated());

        return response()->json([
            'message' => 'Room dates unblocked successfully.',
            'room' => new RoomResource($room),
        ]);
    }

    /**
     * Update Prices
     *
     * Updates the pricing for a room.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrices(UpdateRoomPriceRequest $request, string $id)
    {
        $room = $this->roomUpdateService->updatePrices($id, $request->validated());

        return response()->json([
            'message' => 'Room prices updated successfully.',
            'room' => new RoomResource($room),
        ]);
    }
}
