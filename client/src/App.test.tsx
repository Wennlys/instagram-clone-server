import '@testing-library/jest-dom/extend-expect';
import { cleanup, render } from '@testing-library/react';
import { createMemoryHistory } from 'history';
import React from 'react';
import { act } from 'react-dom/test-utils';
import { MemoryRouter, Router } from 'react-router-dom';
import App from './App';
import api from './services/api';

jest.mock('./services/api');
const mockedApi = api as jest.Mocked<typeof api>;
afterEach(() => {
  cleanup();
  jest.resetAllMocks();
});

const token =
  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MDUxMzQyNDQsImlzcyI6Imluc3RhZ3JhbS5jbG9uZSIsImlhdCI6MTYwNTA0Nzg0NH0.Rg1iGzoCiAl14BPUJQYjm7n941WNlYBmOqGsaruRPBo';

describe('App rendering & routes', () => {
  it('renders Login component', () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 404,
            error: {
              type: 'RESOURCE_NOT_FOUND',
              description: 'Wrong password, try again.',
            },
          },
        ],
      }),
    );
    const { getByText } = render(
      <MemoryRouter>
        <App />
      </MemoryRouter>,
    );
    expect(getByText(/Log In/)).toBeInTheDocument();
  });

  it('renders Homepage component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
            token: token,
          },
        ],
      }),
    );
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
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 404,
            error: {
              type: 'RESOURCE_NOT_FOUND',
              description: 'Wrong password, try again.',
            },
          },
        ],
      }),
    );
    const history = createMemoryHistory();
    history.push('/404');
    await act(async () => {
      const { getByText } = await render(
        <Router history={history}>
          <App />
        </Router>,
      );

      expect(getByText(/Not Found/i)).toBeInTheDocument();
    });
  });

  it('renders Explore component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
            token: token,
          },
        ],
      }),
    );
    const history = createMemoryHistory();
    history.push('/explore');
    await act(async () => {
      const { getByText } = await render(
        <Router history={history}>
          <App />
        </Router>,
      );

      expect(getByText(/Explore/i)).toBeInTheDocument();
    });
  });

  test('hability to authenticate', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
            token: token,
          },
        ],
      }),
    );
    const history = createMemoryHistory();
    history.push('/explore');
    await act(async () => {
      const { getByText } = await render(
        <Router history={history}>
          <App />
        </Router>,
      );

      expect(getByText(/Explore/i)).toBeInTheDocument();
    });
  });
});
