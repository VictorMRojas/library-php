<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Exceptions\BookNotAvailableException;
use App\Exceptions\ReservationAlreadyExistsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations for the authenticated user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $userId = Auth::id();
        
        $reservations = Reservation::with(['user', 'book'])
            ->where('user_id', $userId)
            ->get();
                                    
        return view('reservation.index', compact('reservations'));
    }
    
    /**
     * Store a newly created reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'days_reserved' => 'required|integer|min:1',
            ]);

            // Validate reserved days
            $daysReserved = $request->input('days_reserved');
            if ($daysReserved <= 0) {
                return redirect()->back()->withInput()->withErrors(['days_reserved' => 'Reserved days must be greater than zero.']);
            }

            // Check if the book is available
            $book = Book::findOrFail($request->book_id);
            if (!$book->available) {
                throw new BookNotAvailableException;
            }

            // Check if the reservation already exists
            $existingReservation = Reservation::where('user_id', $request->user_id)
                                            ->where('book_id', $request->book_id)
                                            ->first();

            if ($existingReservation) {
                throw new ReservationAlreadyExistsException;
            }

            // Create the reservation
            Reservation::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'days_reserved' => $daysReserved,
            ]);

            // Update the book availability
            $book->available = false;
            $book->save();

            return redirect()->route('reservation.index')->with('success', 'Reservation created successfully');
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error($e);

            if ($e instanceof ValidationException) {
                Session::flash('error', 'You must specify the reserved days.');
            }
            
            // Handle the exception based on your application's requirements
            if ($e instanceof BookNotAvailableException || 
                $e instanceof ReservationAlreadyExistsException) {
                    Session::flash('error', 'A reservation already exists for this book and user.');
            }
        }
    }

    /**
     * Display the specified reservation.
     *
     * @param  int  $id
     * @return \App\Models\Reservation
     */
    public function show($id)
    {
        return Reservation::with(['user', 'book'])->findOrFail($id);
    }

    /**
     * Update the specified reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'days_reserved' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return response()->json($reservation, 200);
    }

    /**
     * Remove the specified reservation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Update the book availability
        $book = $reservation->book;
        $book->available = true;
        $book->save();
        
        $reservation->delete();

        return redirect()->route('reservation.index')->with('success', 'Reservation deleted successfully');
    }
}
