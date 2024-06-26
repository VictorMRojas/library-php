<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Exceptions\BookNotAvailableException;
use App\Exceptions\ReservationAlreadyExistsException;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    
    public function index()
    {
        $userId = Auth::id();
        
        $reservations = Reservation::with(['user', 'book'])
            ->where('user_id', $userId)
            ->get();
                                    
        return view('reservation.index', compact('reservations'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'days_reserved' => 'required|integer|min:1',
        ]);

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

        $reservation = Reservation::create($request->all());

        // Update the book availability
        $book->available = false;
        $book->save();

        // Update the user's total reservations
        $user = User::findOrFail($request->user_id);
        $user->increment('total_reservations');

        return response()->json($reservation, 201);
    }

    public function show($id)
    {
        return Reservation::with(['user', 'book'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'days_reserved' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return response()->json($reservation, 200);
    }

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