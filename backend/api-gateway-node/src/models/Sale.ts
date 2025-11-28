import mongoose from 'mongoose';

const saleSchema = new mongoose.Schema({
  vendedor_email: { type: String, required: true },
  cliente_nombre: { type: String, required: true },
  
  // --- NUEVOS CAMPOS ---
  cliente_telefono: { type: String, required: true },
  cliente_direccion: { type: String, required: true },
  // ---------------------

  vehiculo_vin: { type: String, required: true }, 
  total: { type: Number, required: true },
  fecha: { type: Date, default: Date.now }
});

export const Sale = mongoose.model('Sale', saleSchema);