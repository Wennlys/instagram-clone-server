import '@testing-library/jest-dom/extend-expect';
import { cleanup, render } from '@testing-library/react';
import { createMemoryHistory } from 'history';
import React from 'react';
import { act } from 'react-dom/test-utils';
import { MemoryRouter, Router } from 'react-router-dom';
import App from './App';
import api from './services/api';

const mockedApi = jest.spyOn(api, 'post');
afterEach(() => {
  cleanup();
});

const token =
  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MDUxMzQyNDQsImlzcyI6Imluc3RhZ3JhbS5jbG9uZSIsImlhdCI6MTYwNTA0Nzg0NH0.Rg1iGzoCiAl14BPUJQYjm7n941WNlYBmOqGsaruRPBo';

describe('App rendering & routes', () => {
  it('renders Login component', () => {
    mockedApi.mockResolvedValue({
      data: [
        {
          statusCode: 404,
          error: {
            type: 'RESOURCE_NOT_FOUND',
            description: 'Wrong password, try again.',
          },
        },
      ],
    });
    const { getByText } = render(
      <MemoryRouter>
        <App />
      </MemoryRouter>,
    );
    expect(getByText(/Log In/)).toBeInTheDocument();
  });

  it('renders Homepage component', async () => {
    mockedApi.mockResolvedValue({
      data: [
        {
          statusCode: 200,
          data: {
            token: token,
          },
        },
      ],
    });
    await act(async () => {
      const { getByText } = await render(
        <MemoryRouter>
          <App />
        </MemoryRouter>,
      );
      expect(getByText(/Home/i)).toBeInTheDocument();
    });
  });

  it('renders 404 component', async () => {
    const history = createMemoryHistory();
    await act(async () => {
      const { getByText } = await render(
        <Router history={history}>
          <App />
        </Router>,
      );

      history.push('/404');
      expect(getByText(/Not Found/i)).toBeInTheDocument();
    });
  });
});
