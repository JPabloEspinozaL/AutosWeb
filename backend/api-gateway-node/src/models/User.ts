import mongoose from 'mongoose';

// Definimos la estructura que pide el PDF (Pág. 22)
const userSchema = new mongoose.Schema({
  nombre: { type: String, required: true },
  email: { type: String, required: true, unique: true }, // El correo no se puede repetir
  password: { type: String, required: true },
  rol: { 
    type: String, 
    enum: ['administrador', 'vendedor', 'consultor'], // Solo permitimos estos 3 roles
    required: true 
  },
  fecha_creacion: { type: Date, default: Date.now }
});

export const User = mongoose.model('User', userSchema);