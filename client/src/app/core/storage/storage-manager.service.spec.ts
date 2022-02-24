import {createServiceFactory, SpectatorService} from "@ngneat/spectator";
import {StorageManager} from "./storage-manager.service";

describe('StorageManager', () => {
  let spectator: SpectatorService<StorageManager>;
  const createService = createServiceFactory(StorageManager);

  beforeEach(() => spectator = createService());

  it('should clear the storage', () => {
    spyOn(localStorage, 'clear');
    spectator.service.clear();
    expect(localStorage.clear).toHaveBeenCalledOnceWith();
  });

  it('should return an item', () => {
    spyOn(localStorage, 'getItem').withArgs('item').and.returnValue('value');
    expect(spectator.service.get('item')).toEqual('value');
    expect(localStorage.getItem).toHaveBeenCalledOnceWith('item');
  });

  it('should set an item', () => {
    spyOn(localStorage, 'setItem').withArgs('item', 'value');
    spectator.service.set('item', 'value');
    expect(localStorage.setItem).toHaveBeenCalledOnceWith('item', 'value');
  });

  it('should delete an item', () => {
    spyOn(localStorage, 'removeItem').withArgs('item');
    spectator.service.remove('item');
    expect(localStorage.removeItem).toHaveBeenCalledOnceWith('item');
  });
});
