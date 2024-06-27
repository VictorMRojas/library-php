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
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'days_reserved' => 'required|integer|min:1',
            ]);

            // Validar días reservados
            $daysReserved = $request->input('days_reserved');
            if ($daysReserved <= 0) {
                return redirect()->back()->withInput()->withErrors(['days_reserved' => 'Los días reservados deben ser mayores a cero.']);
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

            // Crear la reserva
            Reservation::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'days_reserved' => $daysReserved,
            ]);

            // Update the book availability
            $book->available = false;
            $book->save();

            return redirect()->route('reservation.index')->with('success', 'Reserva creada exitosamente');
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error($e);

            if ($e instanceof ValidationException) {
                Session::flash('error', 'Debes indicar los dias reservados.');
            }
            
            // Handle the exception based on your application's requirements
            if ($e instanceof BookNotAvailableException || 
                $e instanceof ReservationAlreadyExistsException) {
                    Session::flash('error', 'Ya existe una reserva para este libro y usuario.');
            }
        }
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