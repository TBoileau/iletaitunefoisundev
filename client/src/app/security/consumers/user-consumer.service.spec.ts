import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {Registration} from "../contracts/register";
import {UserConsumer} from "./user-consumer.service";


describe('User consumer', () => {
  let spectator: SpectatorHttp<UserConsumer>;
  const createHttp = createHttpFactory(UserConsumer);

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
