import {TestBed} from '@angular/core/testing';
import {StorageManager, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";

describe('StorageManager', () => {
  let storageManager: StorageManager;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    storageManager = TestBed.inject(StorageManagerService);
  });

  it('should be created', () => {
    expect(storageManager).toBeTruthy();
  });

  it('should clear the storage', () => {
    spyOn(localStorage, 'clear');
    storageManager.clear();
    expect(localStorage.clear).toHaveBeenCalledOnceWith();
  });

  it('should return an item', () => {
    spyOn(localStorage, 'getItem').withArgs('item').and.returnValue('value');
    expect(storageManager.get('item')).toEqual('value');
    expect(localStorage.getItem).toHaveBeenCalledOnceWith('item');
  });

  it('should set an item', () => {
    spyOn(localStorage, 'setItem').withArgs('item', 'value');
    storageManager.set('item', 'value');
    expect(localStorage.setItem).toHaveBeenCalledOnceWith('item', 'value');
  });

  it('should delete an item', () => {
    spyOn(localStorage, 'removeItem').withArgs('item');
    storageManager.remove('item');
    expect(localStorage.removeItem).toHaveBeenCalledOnceWith('item');
  });
});
