import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {Register, Registration} from "./register.service";

describe('Register', () => {
  let spectator: SpectatorHttp<Register>;
  const createHttp = createHttpFactory(Register);

  beforeEach(() => spectator = createHttp());

  it('should register and return user', () => {
    const registration: Registration = {
      email: 'user@email.com',
      plainPassword: 'password'
    };
    spectator.service.register(registration).subscribe();
    spectator.expectOne('/api/security/register', HttpMethod.POST);
  });
});
