import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { ExpedientesService } from './expedientes.service';
import { CreateExpedienteRequest } from '../models';

describe('ExpedientesService', () => {
  let service: ExpedientesService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [ExpedientesService]
    });

    service = TestBed.inject(ExpedientesService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should fetch expedientes list', () => {
    const mockExpedientes = {
      data: [
        { 
          id: 1, 
          numero: 'EXP-001', 
          estado: 'INGRESADO',
          descripcion: 'Test expediente',
          solicitante_nombre: 'Test User',
          solicitante_dni: '12345678',
          fecha_ingreso: '2024-01-01',
          gerencia_actual_id: 1,
          usuario_actual_id: 1,
          tipo_tramite: { id: 1, nombre: 'Test Tramite' }
        }
      ],
      meta: {
        current_page: 1,
        total: 1
      }
    };

    service.getExpedientes().subscribe(response => {
      expect(response.data.length).toBe(1);
      expect(response.data[0].numero).toBe('EXP-001');
    });

    const req = httpMock.expectOne(request => 
      request.url.includes('/expedientes') && request.method === 'GET'
    );
    req.flush(mockExpedientes);
  });

  it('should create expediente', () => {
    const newExpediente: CreateExpedienteRequest = {
      asunto: 'Solicitud de licencia de funcionamiento',
      descripcion: 'Test expediente',
      solicitante_nombre: 'Test User',
      solicitante_dni: '12345678',
      solicitante_email: 'test@test.com',
      solicitante_telefono: '987654321',
      tipo_tramite: 'licencia_funcionamiento',
      gerencia_id: 1,
      documentos: [{
        nombre: 'test.pdf',
        tipo_documento: 'solicitud',
        archivo: new File(['test'], 'test.pdf', { type: 'application/pdf' })
      }]
    };

    const mockResponse = {
      data: { 
        id: 1, 
        numero: 'EXP-001',
        estado: 'INGRESADO',
        descripcion: newExpediente.descripcion,
        solicitante_nombre: newExpediente.solicitante_nombre,
        solicitante_dni: newExpediente.solicitante_dni,
        fecha_ingreso: '2024-01-01',
        gerencia_actual_id: 1,
        usuario_actual_id: 1,
        tipo_tramite: { id: 1, nombre: 'Test Tramite' }
      }
    };

    service.createExpediente(newExpediente).subscribe(response => {
      expect(response.id).toBe(1);
      expect(response.numero).toBe('EXP-001');
    });

    const req = httpMock.expectOne(request => 
      request.url.includes('/expedientes') && request.method === 'POST'
    );
    req.flush(mockResponse);
  });
});